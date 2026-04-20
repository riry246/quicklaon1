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
        Schema::create('lead_market_buys', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->string('cs_app_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('loan_application_id')->nullable();
            $table->unsignedBigInteger('lead_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->float('saleNetAmount')->nullable();
            $table->float('lead_price')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_market_buys');
    }
};
