<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
        // Eliminar claves foráneas de 'marcas' y 'categorias'
        Schema::table('marcas', function (Blueprint $table) {
            $table->dropForeign(['caracteristica_id']);
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['caracteristica_id']);
        });

        // Ahora podemos eliminar las tablas
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('caracteristicas');
    }

    public function down()
    {
        // Restaurar las tablas
        Schema::create('caracteristicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caracteristica_id')->constrained();
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caracteristica_id')->constrained();
            $table->timestamps();
        });
    }
    
};
