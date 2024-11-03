<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Http\Requests\UpdateProductoRequest2;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use Picqer;
use Carbon\Carbon;

class ProductoController extends Controller implements HasMiddleware
{


    public static function middleware(): array {

        return [
            
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-producto|crear-producto|editar-producto|eliminar-producto'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-producto'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-producto'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-producto'), only:['destroy']),
        ];
     }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();

        return view('producto.index', compact('productos'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('producto.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            DB::beginTransaction();
            $producto = new Producto();
            if ($request->hasFile('img_path')) {
                $name = $producto->hableUploadImage($request->file('img_path'));
            } else {
                $name = null;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'precio_compra' => $request->precio_compra,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'cedula'=> $request->cedula,
                'nombre_del_producto'=> $request->nombre_del_producto,
                'numero_celular'=> $request->numero_celular,
                'monto_interes'=> ($request->precio_compra)*0.25
                
            ]);
            $producto->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }


        return redirect()->route('productos.index')->with('success', 'Producto Registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
      $producto = Producto::all();
      return view('intere.edit', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('producto.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('img_path')) {
                $name = $producto->hableUploadImage($request->file('img_path'));

                if(Storage::disk('public')->exists('/productos'.$producto->img_path)){
                Storage::disk('public')->delete('/productos'.$producto->img_path);
                }
            } else {
                $name = $producto->img_path;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'nombre_del_producto' => $request->nombre_del_producto,
                'cedula' => $request->cedula,
                'numero_celular' => $request->numero_celular,
                'precio_compra' => $request->precio_compra,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
            ]);
            $producto->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('productos.index')->with('success', 'Producto Editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message='';
        $producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 0
                ]);
                $message = 'Producto a Stock';
        } else {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 1
                ]);
                $message='Producto a Empeño';
        }
        return redirect()->route('productos.index')->with('success', $message);
    }

    public function destroya(string $id)
{
    Producto::where('id',$id)->delete();
    return redirect()->route('productos.index')->with('success', 'Producto Eliminado');
}

public function update2(UpdateProductoRequest2 $request, Producto $producto)
{
    try {
        DB::beginTransaction(); // Asegúrate de iniciar la transacción

        // Guardar la fecha anterior de monto_interes_updated_at
        $producto->monto_interes_updated_at_anterior = $producto->monto_interes_updated_at ?? null;

        // Actualizar los campos
        $producto->fill([
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'monto_interes' => $request->monto_interes,
            'monto_interes_updated_at' => Carbon::now(), // Actualiza la fecha
        ]);

        $producto->save();
        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->route('interes.index')->with('error', 'Error al procesar el pago: ' . $e->getMessage());
    }

    return redirect()->route('interes.index')->with('success', 'Pago procesado');
}
public function showModal($id)
    {
        $producto = Producto::findOrFail($id);
    
        $precioCompra = $producto->precio_compra;
        $fechaVencimiento = Carbon::parse($producto->fecha_vencimiento);
        $hoy = Carbon::now();
    
        $mesesPasados = $fechaVencimiento->diffInMonths($hoy);
        
        if ($mesesPasados >= 2) {
            if ($mesesPasados >= 3) {
                $montoInteres = $precioCompra * 0.25 * 3;
                $total = $precioCompra + $montoInteres;
            } else {
                $montoInteres = $precioCompra * 0.25 * 2;
                $total = $precioCompra + $montoInteres;
            }
            
        } else {
            // Si no han pasado 2 meses, el cálculo es distinto
            $montoInteres = 0.25 * $precioCompra;
            $total = $precioCompra + $montoInteres;
        }
        session(['producto_total' => $total]);

        return view('intere.modal-body', [
            'precioCompra' => $precioCompra,
            'montoInteres' => $montoInteres,
            'total' => $total,
            'producto' =>$producto,
        ]);
    }
    public function buscarPorCedula(Request $request)
{
    $cedula = $request->get('cedula');

    // Buscar en la tabla productos por cédula
    $producto = Producto::where('cedula', $cedula)->first();

    if ($producto) {
        // Retornar los datos del producto encontrado como JSON
        return response()->json([
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'numero_celular' => $producto->numero_celular,
        ]);
    }

    // Si no se encuentra nada, retornar un mensaje de error
    return response()->json(null, 404);
}
public function buscarPorNombre(Request $request)
{
    $nombre = $request->get('nombre');

    // Buscar en la tabla productos por nombre
    $producto = Producto::where('nombre', 'like', '%' . $nombre . '%')->first();

    if ($producto) {
        // Retornar los datos del producto encontrado como JSON
        return response()->json([
            'cedula' => $producto->cedula,
            'descripcion' => $producto->descripcion,
            'numero_celular' => $producto->numero_celular,
        ]);
    }

    // Si no se encuentra nada, retornar un mensaje de error
    return response()->json(null, 404);
}

}
