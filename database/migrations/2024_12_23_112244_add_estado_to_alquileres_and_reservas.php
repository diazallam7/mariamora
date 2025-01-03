<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToAlquileresAndReservas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Agregar columna 'estado' a la tabla 'alquileres'
        Schema::table('alquileres', function (Blueprint $table) {
            $table->integer('estado')->default(1)->after('id'); // Valor por defecto 1
        });

        // Agregar columna 'estado' a la tabla 'reservas'
        Schema::table('reservas', function (Blueprint $table) {
            $table->integer('estado')->default(1)->after('id'); // Valor por defecto 1
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar la columna 'estado' de la tabla 'alquileres'
        Schema::table('alquileres', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        // Eliminar la columna 'estado' de la tabla 'reservas'
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
}
