<?php

return [
    'description' => 'Bayar dengan aman menggunakan kartu kredit/debit melalui iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Mengalihkan ke iyzico...',
        'redirecting-to-payment' => 'Mengalihkan ke Pembayaran iyzico',
        'secure-payment' => 'Gerbang Pembayaran Aman',
    ],

    'response' => [
        'cart-not-found' => 'Keranjang tidak ditemukan. Silakan coba lagi.',
        'hash-mismatch' => 'Verifikasi pembayaran gagal. Silakan hubungi dukungan.',
        'initialize-failed' => 'Tidak dapat memulai pembayaran iyzico. Silakan coba lagi.',
        'invalid-transaction' => 'Transaksi tidak valid. Silakan coba lagi.',
        'order-creation-failed' => 'Gagal membuat pesanan. Silakan hubungi dukungan.',
        'payment-failed' => 'Pembayaran gagal. Silakan coba lagi.',
        'payment-success' => 'Pembayaran berhasil diselesaikan!',
        'provide-credentials' => 'Harap konfigurasikan API Key dan Secret Key iyzico di panel admin.',
    ],
];
