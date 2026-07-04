<?php

return [
    'description' => 'Pay securely with credit/debit card via PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Redirecting to PayTR...',
        'redirecting-to-payment' => 'Redirecting to PayTR Payment',
        'secure-payment' => 'Secure Payment Gateway',
    ],

    'response' => [
        'cart-not-found' => 'Cart not found. Please try again.',
        'payment-failed' => 'Payment failed. Please try again.',
        'payment-processing' => 'Your payment is being confirmed. Please check your orders shortly.',
        'payment-success' => 'Payment completed successfully!',
        'provide-credentials' => 'Please configure the PayTR Merchant ID, Merchant Key and Merchant Salt in the admin panel.',
        'token-request-failed' => 'Could not start the PayTR payment. Please try again.',
    ],
];
