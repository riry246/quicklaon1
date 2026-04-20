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
        Schema::create('illion_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_holder');
            $table->string('name');
            $table->string('account_number');
            $table->integer('onlyfromPrimary');
            $table->string('bsb');
            $table->decimal('balance', 10, 2);
            $table->decimal('available', 10, 2);
            $table->string('account_holder_type');
            $table->string('account_type');
            $table->string('institution');
            $table->text('statement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illion_bank_accounts');
    }
};
