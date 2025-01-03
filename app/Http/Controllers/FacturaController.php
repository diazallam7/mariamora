<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Alquiler;
use App\Models\Venta;
use Illuminate\Support\Facades\App;

class FacturaController extends Controller
{
    public function reserva(Reserva $reserva)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('facturas.reserva', compact('reserva'));
        return $pdf->stream("Factura_Reserva_{$reserva->id}.pdf");
    }

    public function alquiler(Alquiler $alquiler)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('facturas.alquiler', compact('alquiler'));
        return $pdf->stream("Factura_Alquiler_{$alquiler->id}.pdf");
    }

    public function venta(Venta $venta)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('facturas.venta', compact('venta'));
        return $pdf->stream("Factura_Venta_{$venta->id}.pdf");
    }
}
