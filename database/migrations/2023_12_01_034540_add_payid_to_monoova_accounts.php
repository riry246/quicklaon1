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
        Schema::table('monoova_accounts', function (Blueprint $table) {
            Schema::table('monoova_accounts', function (Blueprint $table) {
                $table->string('payid')->nullable()->after('bsb');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monoova_accounts', function (Blueprint $table) {
            //
        });
    }
};
