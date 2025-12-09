<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_time_range_operations', function (Blueprint $table) {
            $table->foreignId('parameter_id')->primary()->constrained('parameters')->onDelete('cascade');
            $table->integer('time_window_minutes');
            $table->integer('min_open_trades');
            $table->integer('max_open_trades');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_time_range_operations');
    }
};
