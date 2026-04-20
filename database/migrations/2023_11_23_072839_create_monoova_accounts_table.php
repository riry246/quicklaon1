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
        Schema::create('monoova_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('bankAccountName');
            $table->string('bankAccountNumber');
            $table->string('statusDescription');
            $table->string('bsb');
            $table->string('clientUniqueId');
            $table->string('status');
            $table->string('isActive');
            $table->timestamps();

            // Foreign key relationship with the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monoova_accounts');
    }
};
