<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rule_type_id')->constrained('risk_rule_slugs')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('severity', ['Hard', 'Soft'])->default('Soft');
            $table->boolean('is_active')->default(true);
            $table->foreignId('parameter_id')->nullable()->constrained('parameters')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_rules');
    }
};
