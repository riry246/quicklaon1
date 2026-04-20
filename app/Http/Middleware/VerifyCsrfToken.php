<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'lead-webhook',
        '/lead/timeout',
        '/npp-receive-payment',
        '/illion_test',
        'illion_webhook',
        '/illion_async_report',
        '/loan-application/clear-view-status/*',
        '/sms/webhook'
    ];
}
