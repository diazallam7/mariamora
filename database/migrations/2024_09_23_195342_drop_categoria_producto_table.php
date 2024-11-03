<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCategoriaProductoTable extends Migration
{
    public function up()
    {
        // Eliminar la tabla 'categoria_producto'
        Schema::dropIfExists('categoria_producto');
    }

    public function down()
    {
        // Aquí puedes restaurar la tabla si es necesario
        Schema::create('categoria_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caracteristica_id')->nullable();
            $table->unsignedBigInteger('producto_id')->nullable();

            // Restaurar las claves foráneas
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');

            $table->timestamps();
        });
    }
}
