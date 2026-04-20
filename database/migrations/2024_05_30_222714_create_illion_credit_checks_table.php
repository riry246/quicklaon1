<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('illion_credit_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('application_id');
            $table->bigInteger('consumer_id');
            $table->string('unique_customer_reference');
            $table->string('fullname');
            $table->string('dob');
            $table->string('gender');
            $table->string('address');
            $table->string('drivers_licence')->nullable();
            $table->string('passport')->nullable();
            $table->bigInteger('score');
            $table->longText('credit_report');
            $table->longText('filename');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illion_credit_checks');
    }
};
