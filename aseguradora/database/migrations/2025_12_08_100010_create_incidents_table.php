<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('risk_rule_id')->constrained('risk_rules')->onDelete('cascade');
            $table->foreignId('trade_id')->nullable()->constrained('trades')->onDelete('set null');
            $table->integer('count')->default(1);
            $table->string('triggered_value')->nullable();
            $table->boolean('is_executed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
