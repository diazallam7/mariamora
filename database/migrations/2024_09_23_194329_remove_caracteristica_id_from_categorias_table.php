<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCaracteristicaIdFromCategoriasTable extends Migration
{
    public function up()
    {
        // Primero, eliminamos la clave foránea si existe
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['caracteristica_id']); // Asegúrate de que este sea el nombre correcto de la clave foránea
            $table->dropColumn('caracteristica_id'); // Luego eliminamos la columna
        });
    }

    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->unsignedBigInteger('caracteristica_id')->nullable();
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
        });
    }
}
