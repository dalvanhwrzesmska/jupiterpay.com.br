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
            $table->string('gateway_cashin', 255)->default(null)->after('taxa_cash_out')->nullable();
            $table->string('gateway_cashout', 255)->default(null)->after('gateway_cashin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gateway_cashin');
            $table->dropColumn('gateway_cashout');
        });
    }
};
