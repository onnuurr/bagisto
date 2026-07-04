<?php

return [
    'description' => 'PayTR ile kredi/banka kartınızla güvenli ödeme yapın',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'PayTR\'ye yönlendiriliyor...',
        'redirecting-to-payment' => 'PayTR Ödeme\'ye yönlendiriliyor',
        'secure-payment' => 'Güvenli Ödeme Ağ Geçidi',
    ],

    'response' => [
        'cart-not-found' => 'Sepet bulunamadı. Lütfen tekrar deneyin.',
        'payment-failed' => 'Ödeme başarısız oldu. Lütfen tekrar deneyin.',
        'payment-processing' => 'Ödemeniz onaylanıyor. Lütfen kısa süre sonra siparişlerinizi kontrol edin.',
        'payment-success' => 'Ödeme başarıyla tamamlandı!',
        'provide-credentials' => 'Lütfen yönetici panelinde PayTR Mağaza Numarası, Mağaza Anahtarı ve Mağaza Salt bilgilerini yapılandırın.',
        'token-request-failed' => 'PayTR ödemesi başlatılamadı. Lütfen tekrar deneyin.',
    ],
];
