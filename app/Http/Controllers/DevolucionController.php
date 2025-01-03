<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Configuracion;
use App\Models\Reserva;
use App\Models\Devolucion;
use App\Models\Vestido;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    public function index()
{
    // Obtener los alquileres con estados 2 y 3
    $alquileres = Alquiler::with('cliente', 'vestido')
        ->where('estado',1)
        ->get();

    // Obtener las reservas con estado 1
    $reservas = Reserva::with('cliente', 'vestido')
        ->where('estado', 1)
        ->get();

    // Combinar ambas colecciones con un identificador de tipo
    $registros = $alquileres->map(function ($item) {
        $item->tipo = 'alquiler';
        return $item;
    })->merge(
        $reservas->map(function ($item) {
            $item->tipo = 'reserva';
            return $item;
        })
    );

    return view('alquileres.devoluciones.index', compact('registros'));
}


public function actualizarEstado(Request $request, $id)
{
    $validated = $request->validate([
        'estado' => 'required|in:2,3', // Validar que el estado sea "reservado" (2) o "alquilado" (3)
    ]);

    if ($validated['estado'] == 3) { // Estado "alquilado"
        // Buscar el alquiler
        $alquiler = Alquiler::findOrFail($id);

        // Actualizar el estado del alquiler y el vestido asociado
        $alquiler->update(['estado' => 2]); // Cambiar el estado del alquiler a "finalizado"
        $alquiler->vestido->update(['estado' => 1]); // Cambiar el estado del vestido a "disponible"
    } elseif ($validated['estado'] == 2) { // Estado "reservado"
        // Buscar la reserva
        $reserva = Reserva::findOrFail($id);

        // Actualizar el estado de la reserva y el vestido asociado
        $reserva->update(['estado' => 2]); // Cambiar el estado de la reserva a "finalizado"
        $reserva->vestido->update(['estado' => 1]); // Cambiar el estado del vestido a "disponible"
    }

    return redirect()->route('devoluciones.index')->with('success', 'Vestido Entregado Correctamente!.');
}


public function calcularMultas($id)
{
    // Buscar el alquiler correspondiente con las relaciones necesarias
    $alquiler = Alquiler::with('cliente', 'vestido')->findOrFail($id);

    // Obtener la configuración de la multa diaria
    $multaDiaria = Configuracion::where('nombre', 'multa')->value('valor');

    // Calcular días de retraso manualmente
    $fechaFin = strtotime($alquiler->fecha_fin); // Convertir fecha_fin a timestamp
    $fechaActual = strtotime(now()); // Convertir la fecha actual a timestamp

    $diasRetraso = 0;

    // Calcular la diferencia en días (si aplica)
    if ($fechaActual > $fechaFin) {
        $segundosDiferencia = $fechaActual - $fechaFin;
        $diasRetraso = floor($segundosDiferencia / 86400); // 86400 segundos en un día
    }

    // Calcular multa acumulada solo si hay días de retraso
    $multaTotal = $diasRetraso * $multaDiaria;

    // Pasar datos a la vista
    return view('alquileres.devoluciones.multas', [
        'alquiler' => $alquiler,
        'fechaFin' => date('d/m/Y', $fechaFin),
        'fechaActual' => date('d/m/Y', $fechaActual),
        'diasRetraso' => $diasRetraso,
        'multaDiaria' => $multaDiaria,
        'multaTotal' => $multaTotal,
    ]);
}



}
