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
        Schema::table('app', function (Blueprint $table) {
            $table->string('gateway_cashin_default')->nullable()->after('gerente_percentage');
            $table->string('gateway_cashout_default')->nullable()->after('gateway_cashin_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app', function (Blueprint $table) {
            $table->dropColumn(['gateway_cashin_default', 'gateway_cashout_default']);
        });
    }
};
