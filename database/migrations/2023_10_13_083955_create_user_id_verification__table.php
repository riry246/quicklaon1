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
        Schema::create('user_id_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_information')->nullable();
            $table->string('api_response')->nullable();
            $table->integer('requested_by')->nullable();
            $table->date('requested_date')->nullable();
            $table->date('verified_date')->nullable();
            $table->enum('status', ['verified', 'pending', 'requested', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_id_verification_');
    }
};
