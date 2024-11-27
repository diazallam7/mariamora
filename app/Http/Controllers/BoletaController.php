<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\{WindowsPrintConnector, NetworkPrintConnector};

class BoletaController extends Controller
{
    public function generarBoleta(Request $request)
    {
        // Obtener datos del formulario
        $nombreApellido = $request->input('nombre_apellido');
        $direccion = $request->input('direccion');
        $cedula = $request->input('cedula');
        $telefono = $request->input('telefono');
        $nroBoleta = $request->input('nro_boleta');
        $nombreProducto = $request->input('nombre_producto');
        $total = $request->input('total');
        $fechaEntrega = $request->input('fecha_entrega');

        // Configurar el conector de la impresora
        try {
            // Usa WindowsPrintConnector o NetworkPrintConnector según tu conexión
            $connector = new WindowsPrintConnector("Nombre_Impresora");
            // O para red: $connector = new NetworkPrintConnector("192.168.1.123", 9100);

            $printer = new Printer($connector);

            // Imprimir la boleta (2 veces para dos copias en una sola impresión)
            for ($i = 0; $i < 2; $i++) {
                $printer->setTextSize(1, 1);
                $printer->text("Nombre y Apellido: $nombreApellido\n");
                $printer->text("Direccion: $direccion\n");
                $printer->text("Cedula: $cedula\n");
                $printer->text("Telefono: $telefono\n");
                $printer->text("Nro de Boleta: $nroBoleta\n");
                $printer->text("Nombre del Producto: $nombreProducto\n");
                $printer->text("Total: $total\n");
                $printer->text("Fecha de Entrega: $fechaEntrega\n");
                $printer->feed(2); // Espacio antes de la siguiente boleta
            }

            // Cerrar la impresora
            $printer->cut();
            $printer->close();

            return response()->json(['success' => 'Boletas impresas correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
