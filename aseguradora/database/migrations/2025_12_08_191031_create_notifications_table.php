<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                  ->constrained('user')
                  ->onDelete('cascade');
                  
            $table->json('metadata')->nullable();
            $table->string('mensaje');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};