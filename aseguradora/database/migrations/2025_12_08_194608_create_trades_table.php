<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea a la tabla 'account'
            $table->foreignId('account_id')
                  ->constrained('account')
                  ->onDelete('cascade');
                  
            $table->enum('type', ['BUY', 'SELL']); // Usamos enum para BUY/SELL
            $table->decimal('volume', 10, 4);
            $table->dateTime('open_time');
            $table->dateTime('close_time')->nullable();
            $table->decimal('open_price', 10, 5);
            $table->decimal('close_price', 10, 5)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open'); // Usamos enum para open/closed
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade');
    }
};