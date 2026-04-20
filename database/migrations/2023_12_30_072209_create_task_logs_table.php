<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('loan_application_id')->nullable();
            $table->string('type');
            $table->text('action');
            $table->timestamps();

            // Define foreign key constraints if necessary
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('loan_application_id')->references('id')->on('loan_applications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
