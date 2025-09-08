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
            $table->decimal('baseline', 10, 2)->default(1)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('taxa_boleto_fixa', 10, 2)->default(1)->nullable()->change();
            $table->decimal('taxa_boleto_percentual', 10, 2)->default(1)->nullable()->change();
            $table->decimal('taxa_checkout_fixa', 10, 2)->default(1)->nullable()->change();
            $table->decimal('taxa_checkout_porcentagem', 10, 2)->default(1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
