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
    Schema::create('polizas', function (Blueprint $table) {
        $table->id();
        // AÑADIR LAS COLUMNAS FALTANTES AQUÍ:
        $table->string('numero_poliza')->unique(); 
        $table->string('cliente');
        $table->decimal('monto', 10, 2); 
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        // FIN DE LAS COLUMNAS FALTANTES
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};
