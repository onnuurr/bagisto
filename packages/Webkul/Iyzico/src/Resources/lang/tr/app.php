<?php

return [
    'description' => 'iyzico ile kredi/banka kartınızla güvenli ödeme yapın',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'iyzico\'ya yönlendiriliyor...',
        'redirecting-to-payment' => 'iyzico Ödeme\'ye yönlendiriliyor',
        'secure-payment' => 'Güvenli Ödeme Ağ Geçidi',
    ],

    'response' => [
        'cart-not-found' => 'Sepet bulunamadı. Lütfen tekrar deneyin.',
        'hash-mismatch' => 'Ödeme doğrulaması başarısız oldu. Lütfen destekle iletişime geçin.',
        'initialize-failed' => 'iyzico ödemesi başlatılamadı. Lütfen tekrar deneyin.',
        'invalid-transaction' => 'Geçersiz işlem. Lütfen tekrar deneyin.',
        'order-creation-failed' => 'Sipariş oluşturulamadı. Lütfen destekle iletişime geçin.',
        'payment-failed' => 'Ödeme başarısız oldu. Lütfen tekrar deneyin.',
        'payment-success' => 'Ödeme başarıyla tamamlandı!',
        'provide-credentials' => 'Lütfen yönetici panelinde iyzico API Anahtarı ve Gizli Anahtarı yapılandırın.',
    ],
];
