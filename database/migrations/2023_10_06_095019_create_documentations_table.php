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
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_application_id');
            $table->string('document_type');
            $table->text('filename');
            $table->enum('status', [1, 0])->default(1);
            $table->datetime('requested_at');
            $table->integer('requested_by');
            $table->datetime('verified_at');
            $table->integer('verified_by');
            $table->foreign('loan_application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
