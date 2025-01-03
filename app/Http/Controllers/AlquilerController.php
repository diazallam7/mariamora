<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Vestido;
use Illuminate\Http\Request;

class AlquilerController extends Controller
{
    public function index()
    {
        $alquileres = Alquiler::all();
    
        return view('alquileres.index', compact('alquileres'));
    }
    

    public function create()
    {
        $clientes = Cliente::all();
        $vestidos = Vestido::where('estado', 1)->get();
        return view('alquileres.create', compact('clientes', 'vestidos'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'vestido_id' => 'required|exists:vestidos,id',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after:fecha_inicio',
        'costo_total' => 'required|numeric|min:0',
    ]);
    

    // Cambiar el estado del vestido a alquilado
    $vestido = Vestido::findOrFail($validated['vestido_id']);
    $vestido->update(['estado' => 3]);

    Alquiler::create($validated);

    return redirect()->route('alquileres.index');
}


    public function destroy(Alquiler $alquiler)
    {
        $alquiler->delete();
        return redirect()->route('alquileres.index');
    }

    public function devolver(Alquiler $alquiler)
{
    // Cambiar el estado del alquiler a "completado"
    $alquiler->update(['estado' => 'completado']);

    // Cambiar el estado del vestido a "disponible"
    $alquiler->vestido->update(['estado' => 'disponible']);

    return redirect()->route('alquileres.index')->with('success', 'El vestido fue devuelto con éxito.');
}

}
