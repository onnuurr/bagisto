<?php

return [
    'description' => 'Pay securely with credit/debit card via iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Redirecting to iyzico...',
        'redirecting-to-payment' => 'Redirecting to iyzico Payment',
        'secure-payment' => 'Secure Payment Gateway',
    ],

    'response' => [
        'cart-not-found' => 'Cart not found. Please try again.',
        'hash-mismatch' => 'Payment verification failed. Please contact support.',
        'initialize-failed' => 'Could not start the iyzico payment. Please try again.',
        'invalid-transaction' => 'Invalid transaction. Please try again.',
        'order-creation-failed' => 'Failed to create order. Please contact support.',
        'payment-failed' => 'Payment failed. Please try again.',
        'payment-success' => 'Payment completed successfully!',
        'provide-credentials' => 'Please configure the iyzico API Key and Secret Key in the admin panel.',
    ],
];
