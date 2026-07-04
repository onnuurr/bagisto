<?php

return [
    'description' => 'Bayar dengan aman menggunakan kartu kredit/debit melalui PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Mengalihkan ke PayTR...',
        'redirecting-to-payment' => 'Mengalihkan ke Pembayaran PayTR',
        'secure-payment' => 'Gerbang Pembayaran Aman',
    ],

    'response' => [
        'cart-not-found' => 'Keranjang tidak ditemukan. Silakan coba lagi.',
        'payment-failed' => 'Pembayaran gagal. Silakan coba lagi.',
        'payment-processing' => 'Pembayaran Anda sedang dikonfirmasi. Silakan periksa pesanan Anda sebentar lagi.',
        'payment-success' => 'Pembayaran berhasil diselesaikan!',
        'provide-credentials' => 'Harap konfigurasikan Merchant ID, Merchant Key, dan Merchant Salt PayTR di panel admin.',
        'token-request-failed' => 'Tidak dapat memulai pembayaran PayTR. Silakan coba lagi.',
    ],
];
