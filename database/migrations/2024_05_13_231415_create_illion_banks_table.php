<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIllionBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('illion_banks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->json('credentials');
            $table->string('status')->nullable();
            $table->string('severity')->nullable();
            $table->boolean('present')->nullable();
            $table->string('present_service')->nullable();
            $table->boolean('available')->nullable();
            $table->boolean('searchable')->nullable();
            $table->boolean('display')->nullable();
            $table->string('searchVal')->nullable();
            $table->string('region')->nullable();
            $table->boolean('export_with_password')->nullable();
            $table->boolean('estatements_supported')->nullable();
            $table->boolean('transaction_listings_supported')->nullable();
            $table->boolean('card_validation_supported')->nullable();
            $table->boolean('requires_preload')->nullable();
            $table->boolean('requires_mfa')->nullable();
            $table->boolean('is_business_bank')->nullable();
            $table->boolean('ocr_supported')->nullable();
            $table->string('type')->nullable();
            $table->boolean('do_not_proxy')->nullable();
            $table->integer('max_days')->nullable();
            $table->boolean('get_address_supported')->nullable();
            $table->boolean('supports_payment_summaries')->nullable();
            $table->boolean('is_supported')->nullable();
            $table->boolean('is_override_status_message')->nullable();
            $table->boolean('hide_merged_estatement_privacy_note')->nullable();
            $table->timestamp('time_next_stats_cron')->nullable();
            $table->timestamp('time_next_session_cron')->nullable();
            $table->timestamp('time_success_rate_updated')->nullable();
            $table->boolean('current_success_rate_ignore_abandoned')->nullable();
            $table->boolean('current_success_rate_ignore_failed_login')->nullable();
            $table->boolean('current_success_rate_all')->nullable();
            $table->integer('sessions_per_week')->nullable();
            $table->timestamp('time_next_outages_cron')->nullable();
            $table->string('cdr_identifier')->nullable();
            $table->string('scrapping_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('illion_banks');
    }
}

