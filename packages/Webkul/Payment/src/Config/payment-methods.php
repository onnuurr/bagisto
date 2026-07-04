<?php

use Webkul\Payment\Payment\CashOnDelivery;
use Webkul\Payment\Payment\LocalPayment;
use Webkul\Payment\Payment\MoneyTransfer;

return [
    'cashondelivery' => [
        'class' => CashOnDelivery::class,
        'code' => 'cashondelivery',
        'title' => 'Cash On Delivery',
        'description' => 'Cash On Delivery',
        'active' => true,
        'generate_invoice' => false,
        'sort' => 7,
    ],

    'moneytransfer' => [
        'class' => MoneyTransfer::class,
        'code' => 'moneytransfer',
        'title' => 'Money Transfer',
        'description' => 'Money Transfer',
        'active' => true,
        'generate_invoice' => false,
        'sort' => 8,
    ],

    'local_payment' => [
        'class' => LocalPayment::class,
        'code' => 'local_payment',
        'title' => 'Local Payment',
        'description' => 'Local Payment',
        'active' => false,
        'generate_invoice' => false,
        'sort' => 9,
    ],
];
