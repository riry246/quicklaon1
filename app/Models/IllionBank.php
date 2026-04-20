<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IllionBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'credentials',
        'status',
        'severity',
        'present',
        'present_service',
        'available',
        'searchable',
        'display',
        'searchVal',
        'region',
        'export_with_password',
        'estatements_supported',
        'transaction_listings_supported',
        'card_validation_supported',
        'requires_preload',
        'requires_mfa',
        'is_business_bank',
        'ocr_supported',
        'type',
        'do_not_proxy',
        'updated_at',
        'max_days',
        'get_address_supported',
        'supports_payment_summaries',
        'is_supported',
        'is_override_status_message',
        'hide_merged_estatement_privacy_note',
        'time_next_stats_cron',
        'time_next_session_cron',
        'time_success_rate_updated',
        'current_success_rate_ignore_abandoned',
        'current_success_rate_ignore_failed_login',
        'current_success_rate_all',
        'sessions_per_week',
        'time_next_outages_cron',
        'cdr_identifier',
        'scrapping_method'
    ];
}
