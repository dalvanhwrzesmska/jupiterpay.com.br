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
        Schema::table('users', function (Blueprint $table) {
            $table->string('taxa_cartao_1x')->default(null)->after('webhook_endpoint')->nullable();
            $table->string('taxa_cartao_2x')->default(null)->after('taxa_cartao_1x')->nullable();
            $table->string('taxa_cartao_3x')->default(null)->after('taxa_cartao_2x')->nullable();
            $table->string('taxa_cartao_4x')->default(null)->after('taxa_cartao_3x')->nullable();
            $table->string('taxa_cartao_5x')->default(null)->after('taxa_cartao_4x')->nullable();
            $table->string('taxa_cartao_6x')->default(null)->after('taxa_cartao_5x')->nullable();
            $table->string('taxa_cartao_7x')->default(null)->after('taxa_cartao_6x')->nullable();
            $table->string('taxa_cartao_8x')->default(null)->after('taxa_cartao_7x')->nullable();
            $table->string('taxa_cartao_9x')->default(null)->after('taxa_cartao_8x')->nullable();
            $table->string('taxa_cartao_10x')->default(null)->after('taxa_cartao_9x')->nullable();
            $table->string('taxa_cartao_11x')->default(null)->after('taxa_cartao_10x')->nullable();
            $table->string('taxa_cartao_12x')->default(null)->after('taxa_cartao_11x')->nullable();

            $table->string('taxa_boleto_fixa')->default(null)->after('taxa_cartao_12x')->nullable();
            $table->string('taxa_boleto_percentual')->default(null)->after('taxa_boleto_fixa')->nullable();

            $table->string('taxa_checkout_fixa')->default(null)->after('taxa_boleto_percentual')->nullable();
            $table->string('taxa_checkout_porcentagem')->default(null)->after('taxa_checkout_fixa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('taxa_cartao_1x');
            $table->dropColumn('taxa_cartao_2x');
            $table->dropColumn('taxa_cartao_3x');
            $table->dropColumn('taxa_cartao_4x');
            $table->dropColumn('taxa_cartao_5x');
            $table->dropColumn('taxa_cartao_6x');
            $table->dropColumn('taxa_cartao_7x');
            $table->dropColumn('taxa_cartao_8x');
            $table->dropColumn('taxa_cartao_9x');
            $table->dropColumn('taxa_cartao_10x');
            $table->dropColumn('taxa_cartao_11x');
            $table->dropColumn('taxa_cartao_12x');
            $table->dropColumn('taxa_boleto_cash_in_fixo');
            $table->dropColumn('taxa_boleto_cash_in_porcentagem');
            $table->dropColumn('taxa_checkout_fixa');
            $table->dropColumn('taxa_checkout_porcentagem');
        });
    }
};
