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
        Schema::create('loan_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_application_id');
            $table->decimal('opening_balance', 10, 2);
            $table->decimal('weekly_payment', 10, 2);
            $table->decimal('interest', 10, 2);
            $table->decimal('principal_payment', 10, 2);
            $table->decimal('closing_balance', 10, 2);
            $table->date('payment_date');
            $table->string('payment_status');
            $table->timestamps();

            // Define foreign key constraint to link to the loans table
            $table->foreign('loan_application_id')->references('id')->on('loan_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_statements');
    }
};
