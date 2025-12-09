<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rule_actions', function (Blueprint $table) {
            $table->foreignId('risk_rule_id')->constrained('risk_rules')->onDelete('cascade');
            $table->foreignId('risk_action_id')->constrained('risk_actions')->onDelete('cascade');
            $table->primary(['risk_rule_id', 'risk_action_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rule_actions');
    }
};
