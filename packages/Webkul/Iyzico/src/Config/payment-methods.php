<?php

use Webkul\Iyzico\Payment\Iyzico;

return [
    'iyzico' => [
        'class' => Iyzico::class,
        'code' => 'iyzico',
        'title' => 'iyzico',
        'description' => 'iyzico',
        'active' => true,
        'sandbox' => true,
        'api_key' => 'API_KEY',
        'secret_key' => 'SECRET_KEY',
        'currency' => 'TRY',
        'sort' => 12,
    ],
];
