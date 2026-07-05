<?php

return [
    'flatrate' => [
        'code' => 'flatrate',
        'title' => 'Flat Rate',
        'description' => 'Flat Rate Shipping',
        'active' => true,
        'default_rate' => '10',
        'type' => 'per_unit',
        'class' => 'Webkul\Shipping\Carriers\FlatRate',
    ],

    'free' => [
        'code' => 'free',
        'title' => 'Free Shipping',
        'description' => 'Free Shipping',
        'active' => true,
        'default_rate' => '0',
        'class' => 'Webkul\Shipping\Carriers\Free',
    ],

    'araskargo' => [
        'code' => 'araskargo',
        'title' => 'Aras Kargo',
        'description' => 'Aras Kargo Shipping',
        'active' => false,
        'default_rate' => '0',
        'class' => 'Webkul\Shipping\Carriers\ArasKargo',
    ],

    'kargonomi' => [
        'code' => 'kargonomi',
        'title' => 'Kargonomi',
        'description' => 'Kargonomi Shipping',
        'active' => false,
        'default_rate' => '0',
        'class' => 'Webkul\Shipping\Carriers\Kargonomi',
    ],
];
