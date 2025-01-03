<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Vestido;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::all();
    
        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $vestidos = Vestido::where('estado', 1)->get();
        return view('reservas.create', compact('clientes', 'vestidos'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'vestido_id' => 'required|exists:vestidos,id',
        'fecha_reserva' => 'required|date',
        'fecha_evento' => 'required|date',
    ]);

    // Cambiar el estado del vestido a reservado
    $vestido = Vestido::findOrFail($validated['vestido_id']);
    $vestido->update(['estado' => 2]);

    Reserva::create($validated);

    return redirect()->route('reservas.index');
}

public function alquilar(Request $request, $id)
{
    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after:fecha_inicio',
    ]);

    $reserva = Reserva::findOrFail($id);

    // Crear el alquiler con los datos de la reserva
    $alquiler = Alquiler::create([
        'cliente_id' => $reserva->cliente_id,
        'vestido_id' => $reserva->vestido_id,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
        'costo_total' => $reserva->vestido->precio_alquiler, // Tomar el costo del vestido
    ]);

    // Actualizar el estado de la reserva y del vestido
    $reserva->update(['estado' => 2]); // Estado completado para la reserva
    $reserva->vestido->update(['estado' => 3]); // Estado alquilado para el vestido

    return redirect()->route('reservas.index')->with('success', 'Alquiler registrado correctamente.');
}



    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index');
    }

}
