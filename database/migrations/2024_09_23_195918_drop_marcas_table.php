<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropMarcasTable extends Migration
{
    public function up()
    {
        // Eliminar la tabla 'marcas'
        Schema::dropIfExists('marcas');
    }

    public function down()
    {
        // Aquí puedes restaurar la tabla si es necesario
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Cambia el tipo según sea necesario
            $table->timestamps(); // O agrega otras columnas según tu diseño
        });
    }
}

