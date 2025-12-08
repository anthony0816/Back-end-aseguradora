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
    Schema::create('accounts', function (Blueprint $table) {
        $table->id();
        
        // 🔑 CAMPO CLAVE: login (numérico, único)
        $table->unsignedBigInteger('login')->unique(); 
        
        // Estatus de Trading (solo acepta 'enable' o 'disable')
        $table->enum('trading_status', ['enable', 'disable'])->default('enable');
        
        // Estatus general de la Cuenta (solo acepta 'enable' o 'disable')
        $table->enum('status', ['enable', 'disable'])->default('enable');
        
        $table->timestamps(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
