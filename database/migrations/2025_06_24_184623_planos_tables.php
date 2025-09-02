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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->decimal('taxa_cash_in', 5, 2)->default(0.00);
            $table->decimal('taxa_cash_out', 5, 2)->default(0.00);
            $table->integer('expiration')->default(30); // duração em dias
            $table->boolean('active')->default(true);
            $table->boolean('custom')->default(false);
            $table->timestamps();
        });

        Schema::create('plan_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->timestamp('date_start')->useCurrent();
            $table->timestamp('date_end')->nullable();
            $table->timestamps();
            // Removido unique(['user_id', 'plan_id']) para permitir histórico
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->string('feature');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['plan_id', 'feature']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
        Schema::dropIfExists('plan_user');
        Schema::dropIfExists('plan_features');
    }
};
