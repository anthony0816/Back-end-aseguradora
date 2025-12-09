<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_volume_trades', function (Blueprint $table) {
            $table->foreignId('parameter_id')->primary()->constrained('parameters')->onDelete('cascade');
            $table->decimal('min_factor', 10, 4);
            $table->decimal('max_factor', 10, 4);
            $table->integer('lookback_trades');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_volume_trades');
    }
};
