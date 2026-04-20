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
        Schema::create('cash_faster_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('ref_code');
            $table->string('filename')->nullable();
            $table->string('status');
            $table->string('expiration_date')->nullable();
            $table->string('share_link_customer')->nullable();
            $table->string('ip_address_customer')->nullable();
            $table->string('viwe_date_customer')->nullable();
            $table->string('signed_date_customer')->nullable();
            $table->string('signature_type_customer')->nullable();
            $table->text('signature_customer')->nullable();
            $table->string('share_link_cf')->nullable();
            $table->string('ip_address_cf')->nullable();
            $table->string('viwe_date_cf')->nullable();
            $table->string('signed_date_cf')->nullable();
            $table->string('signature_type_cf')->nullable();
            $table->text('signature_cf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_faster_contracts');
    }
};
