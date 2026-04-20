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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('account_number')->nullable()->after('id');
            $table->string('account_name')->nullable()->after('account_number');
            $table->string('bsb')->nullable()->after('account_name');
            $table->string('institution')->nullable()->after('bsb');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction', function (Blueprint $table) {
            //
        });
    }
};
