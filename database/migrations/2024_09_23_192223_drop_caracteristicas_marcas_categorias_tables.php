<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('caracteristicas');
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('categorias');
    }
    
    public function down()
    {
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
