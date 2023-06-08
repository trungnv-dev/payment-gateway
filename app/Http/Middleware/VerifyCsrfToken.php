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
        'https://payment-gateway.test/payment/gmo/order/*/secure',
        'https://payment-gateway.test/payment/gmo/order/*/secure/2',
        'https://payment-gateway.test/paypay/result',
    ];
}
