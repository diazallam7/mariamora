<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompraRequest;
use App\Models\CierreCaja;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class compraController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {

        return [

            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-compra|crear-compra|mostrar-compra|eliminar-compra'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-compra'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('mostrar-compra'), only: ['show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-compra'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('comprobante')
            ->where('estado', 1)
            ->latest()
            ->get();
        return view('compra.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comprobantes = Comprobante::all();
        $productos = Producto::where('estado', 0)->get();
        $venta = Venta::where('estado', 1)->get();
        return view('compra.create', compact('comprobantes', 'productos', 'venta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
    {
        try {
            DB::beginTransaction();
            $compra = Compra::create($request->validated());
            $arrayProducto_id = $request->get('arrayidProducto');
            $arrayVenta_id = $request->get('arrayidVenta');
            $arrayCantidad = $request->get('arrayCantidad');
            $arrayPrecioCompra = $request->get('arrayprecioCompra');
            $arrayPrecioVenta = $request->get('arrayprecioVenta');
            $cont = 0;

            if ($arrayProducto_id != '') {
                $siseArray = count($arrayProducto_id);
                while ($cont < $siseArray) {
                    $compra->productos()->syncWithoutDetaching([
                        $arrayProducto_id[$cont] => [
                            'cantidad' => $arrayCantidad[$cont],
                            'precio_compra' => $arrayPrecioCompra[$cont],
                            'precio_venta' => $arrayPrecioVenta[$cont],
                        ],
                    ]);

                    // Cambiar el estado del producto a 2 (vendido)
                    $producto = Producto::find($arrayProducto_id[$cont]);
                    $producto->estado = 2;
                    $producto->save();
                    $cont++;

                }
            } else {
                $siseArray = count($arrayVenta_id);
                while ($cont < $siseArray) {
                    $compra->ventas()->syncWithoutDetaching([
                        $arrayVenta_id[$cont] => [
                            'cantidad' => $arrayCantidad[$cont],
                            'precio_compra' => $arrayPrecioCompra[$cont],
                            'precio_venta' => $arrayPrecioVenta[$cont],
                        ],
                    ]);

                    // Cambiar el estado de la venta a 2 (vendido)
                    $venta = Venta::find($arrayVenta_id[$cont]);
                    $venta->estado = 0;
                    $venta->save();
                    $cont++;
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('compras.index')->with('success', 'Compra Exitosa');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
{
    $productos = collect();
    $ventas = collect();
    
    // Verifica si la compra tiene productos relacionados
    if ($compra->productos()->exists()) {
        $productos = $compra->productos()->select('nombre_del_producto')->get();
    }

    // Verifica si la compra tiene ventas relacionadas
    if ($compra->ventas()->exists()) {
        $ventas = $compra->ventas()->select('nombre_producto')->get();
    }
    
    // Combina productos y ventas en un solo array
    $items = $productos->merge($ventas);

    return view('compra.show', compact('compra', 'items'));
}

    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Compra::where('id', $id)
            ->update([
                'estado' => 0,
            ]);

        return redirect()->route('compras.index')->with('success', 'Compra Eliminada');
    }

    public function cierre_caja(Request $request)
    {
        // Buscar el último cierre de caja registrado
        $cierreHoy = CierreCaja::orderBy('created_at', 'desc')->first();
    
        // Calcular el tiempo desde el último cierre de caja
        $tiempoDesdeUltimoCierre = $cierreHoy ? Carbon::parse($cierreHoy->created_at)->diffInHours(Carbon::now()) : null;
    
        // Si no hay cierre de caja o si han pasado más de 20 horas desde el último cierre
        if (!$cierreHoy || $tiempoDesdeUltimoCierre >= 20) {
            // Sumar las ventas de la tabla compras desde el último cierre de caja
            $totalVentas = DB::table('compra_producto')
                ->where('created_at', '>=', $cierreHoy ? $cierreHoy->created_at : Carbon::now()->subHours(20)) // Sumar desde el último cierre o desde hace 20 horas si es el primer cierre
                ->sum('precio_venta');
    
            $totalVentasb = DB::table('compra_venta')
                ->where('created_at', '>=', $cierreHoy ? $cierreHoy->created_at : Carbon::now()->subHours(20))
                ->sum('precio_venta');
    
            // Sumar los productos cuyo estado es 2 y cuyo precio_compra ha sido modificado
            $totalProductosModificados = DB::table('productos')
                ->where('estado', 2) // Solo productos en estado 2 (retirados)
                ->where('estado_anterior', 1) // Verificar que ya estaban en estado 2 antes de la actualización
                ->whereColumn('precio_compra', '<>', 'created_at') // Verificar que el precio_compra ha sido modificado
                ->where('updated_at', '>=', $cierreHoy ? $cierreHoy->created_at : Carbon::now()->subHours(20)) // Sumar desde el último cierre o hace 20 horas
                ->sum('precio_compra');
    
            $totalVentas += $totalProductosModificados + $totalVentasb;
    
            // Sumar el monto_interes solo si ha sido actualizado después del último cierre de caja
            $montoInteres = DB::table('productos')
                ->where('estado', 1)
                ->whereNotNull('monto_interes_updated_at') // Verifica que haya sido actualizado
                ->where('monto_interes_updated_at', '>', $cierreHoy ? $cierreHoy->created_at : null) // Solo suma si fue actualizado después del último cierre
                ->sum('monto_interes');
    
            // Sumar el monto_interes a las ventas totales
            $totalVentas += $montoInteres;
    
            // Sumar las compras de la tabla productos y de la tabla ventas en las últimas 20 horas o desde el último cierre
            $totalComprasProductos = DB::table('productos')
                ->where('created_at', '>=', $cierreHoy ? $cierreHoy->created_at : Carbon::now()->subHours(20))
                ->sum('precio_compra');
    
            $totalComprasVentas = DB::table('ventas')
                ->where('created_at', '>=', $cierreHoy ? $cierreHoy->created_at : Carbon::now()->subHours(20))
                ->sum('precio_compra');
    
            $totalCompras = $totalComprasProductos + $totalComprasVentas;
    
            $montoExtra = $request->input('monto_extra', 0);
    
            // Crear un nuevo registro de cierre de caja
            $cierreHoy = CierreCaja::create([
                'total_ventas' => $totalVentas,
                'total_compras' => $totalCompras,
                'monto_extra' => $montoExtra,
            ]);
        } else {
            // Si ya existe un cierre y no han pasado 20 horas, solo actualizar el monto extra ingresado
            $montoExtra = $request->input('monto_extra', 0);
            $cierreHoy->monto_extra = $montoExtra;
            $cierreHoy->save();
        }
    
        $totalComprasConExtra = $cierreHoy->total_compras + $cierreHoy->monto_extra;
    
        return view('compra.cierre_caja', [
            'totalVentas' => $cierreHoy->total_ventas,
            'totalCompras' => $cierreHoy->total_compras,
            'montoExtra' => $cierreHoy->monto_extra,
            'totalComprasConExtra' => $totalComprasConExtra,
        ]);
    }
    
}

