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
        Schema::create('credit_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->integer('score_value');
            $table->integer('score_card_num');
            $table->string('system_message_description');
            $table->int('system_message_code');
            $table->text('response');
            $table->foreign('application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_scores');
    }
};
