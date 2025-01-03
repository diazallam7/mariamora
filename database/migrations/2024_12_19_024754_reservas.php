<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Reservas extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('vestido_id')->constrained('vestidos')->onDelete('cascade');
            $table->date('fecha_reserva');
            $table->date('fecha_evento');
            $table->enum('estado', ['activa', 'cancelada'])->default('activa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }

};
