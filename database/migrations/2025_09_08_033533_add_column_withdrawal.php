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
        Schema::table("users", function (Blueprint $table) {
            $table->decimal('taxa_cash_in_fixa', 5, 2)->default(0)->after('taxa_cash_out');
            $table->decimal('taxa_cash_out_fixa', 5, 2)->default(0)->after('taxa_cash_in_fixa');
            $table->string('tax_method')->default('external')->after('taxa_cash_out_fixa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['taxa_cash_in_fixa', 'taxa_cash_out_fixa', 'tax_method']);
        });
    }
};
