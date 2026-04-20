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
        Schema::table('loan_statements', function (Blueprint $table) {
            $table->decimal('weekly_establishment_fee', 10, 2)->after('closing_balance')->nullable();
                $table->decimal('weekly_interest', 10, 2)->after('closing_balance')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_statements', function (Blueprint $table) {
            //
        });
    }
};
