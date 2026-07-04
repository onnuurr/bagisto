<?php

use Webkul\PayTR\Payment\PayTR;

return [
    'paytr' => [
        'class' => PayTR::class,
        'code' => 'paytr',
        'title' => 'PayTR',
        'description' => 'PayTR',
        'active' => true,
        'sandbox' => true,
        'merchant_id' => 'MERCHANT_ID',
        'merchant_key' => 'MERCHANT_KEY',
        'merchant_salt' => 'MERCHANT_SALT',
        'currency' => 'TL',
        'sort' => 10,
    ],
];
