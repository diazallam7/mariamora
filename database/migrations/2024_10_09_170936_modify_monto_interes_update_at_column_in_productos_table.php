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
            $table->timestamp('monto_interes_update_at')->useCurrent()->change(); // Modificar la columna para usar la fecha actual por defecto
        });
    }
    
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->timestamp('monto_interes_update_at')->nullable()->change(); // Revertir a nullable si es necesario
        });
    }
    
};
