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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('public_code')->nullable();
            $table->string('basiq_code')->nullable();
            $table->integer('illion_with_mfa')->nullable();
            $table->string('short_name')->nullable();
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('bank_tier')->nullable();
            $table->string('crm_name')->nullable();
            $table->string('username')->nullable();
            $table->string('logo')->nullable();
            $table->string('password')->nullable();
            $table->string('secret')->nullable();
            $table->string('basiq_stage')->nullable();
            $table->string('basiq_status')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
