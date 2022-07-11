<?php

return [
    'site_id'     => env('SITE_ID', ''),
    'site_pass'   => env('SITE_PASS', ''),
    'shop_id'     => env('SHOP_ID', ''),
    'shop_pass'   => env('SHOP_PASS', ''),
    'gmo_url'     => env('GMO_URL', ''),
    'job_cd'      => [
        'check'   => 'CHECK',   // Validity check
        'capture' => 'CAPTURE', // Instant capture
        'auth'    => 'AUTH',    // Authorization
        'sauth'   => 'SAUTH',   // Simple authorization
        'void'    => 'VOID',
    ],
    'order_stt'   => [
        0 => 'UNPAID',
        1 => 'PAID'
    ],
];