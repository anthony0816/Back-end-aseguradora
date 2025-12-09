<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duration_parameters', function (Blueprint $table) {
            $table->foreignId('parameter_id')->primary()->constrained('parameters')->onDelete('cascade');
            $table->integer('duration');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duration_parameters');
    }
};
