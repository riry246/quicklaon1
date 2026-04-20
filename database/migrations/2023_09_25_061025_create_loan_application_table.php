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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->float('amount');
            $table->integer('duration');
            $table->date('application_date');
            $table->integer('reviewed_by')->nullable();
            $table->date('reviewed_date')->nullable();
            $table->float('approved_amount')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->integer('credit_score')->nullable();
            $table->enum('status', ['active', 'declined', 'pending', 'processing', 'completed', 'incomplete']);
            $table->integer('step');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_application');
    }
};
