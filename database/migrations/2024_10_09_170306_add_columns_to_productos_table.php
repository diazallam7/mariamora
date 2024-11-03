<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->timestamp('monto_interes_update_at')->nullable(); // Columna para registrar fecha y hora actual
            $table->string('estado_anterior')->nullable(); // Columna para registrar el estado anterior
        });
    }
    
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('monto_interes_update_at');
            $table->dropColumn('estado_anterior');
        });
    }
    
};
