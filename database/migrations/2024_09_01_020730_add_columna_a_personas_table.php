<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       
        Schema::table('personas', function (Blueprint $table) {
            // Agregar una nueva columna 'telefono' de tipo string
            $table->string('correo')->nullable(); // 'nullable()' es opcional dependiendo de tus necesidades
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la columna 'telefono'
            $table->dropColumn('correo');
        });
    }
};
