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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('loan_statements_id')->nullable();
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('amount',8,2);
            $table->string('status');
            $table->string('status_description');
            $table->string('description');
            $table->json('bpay_receipts');
            $table->uuid('caller_unique_reference');
            $table->string('type');
            $table->decimal('fee_amount_excluding_gst', 8, 3);
            $table->decimal('fee_amount_gst_component', 8, 3);
            $table->decimal('fee_amount_including_gst', 8, 3);
            $table->json('fee_breakdown');
            $table->integer('duration_ms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
