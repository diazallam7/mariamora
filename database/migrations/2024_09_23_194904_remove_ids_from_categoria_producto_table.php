<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIdsFromCategoriaProductoTable extends Migration
{
    public function up()
    {
        Schema::table('categoria_producto', function (Blueprint $table) {
            // Primero, eliminamos las claves foráneas si existen
            $table->dropForeign(['categoria_id']); // Asegúrate de que este sea el nombre correcto de la clave foránea
            $table->dropForeign(['producto_id']); // Asegúrate de que este sea el nombre correcto de la clave foránea

            // Luego eliminamos las columnas
            $table->dropColumn(['categoria_id', 'producto_id']);
        });
    }

    public function down()
    {
        Schema::table('categoria_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('producto_id')->nullable();

            // Restaurar las claves foráneas
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });
    }
}
