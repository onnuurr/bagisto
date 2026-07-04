<?php

return [
    'description' => 'Sicher mit Kredit-/Debitkarte über PayTR bezahlen',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Weiterleitung zu PayTR...',
        'redirecting-to-payment' => 'Weiterleitung zur PayTR-Zahlung',
        'secure-payment' => 'Sichere Zahlungsgateway',
    ],

    'response' => [
        'cart-not-found' => 'Warenkorb nicht gefunden. Bitte versuchen Sie es erneut.',
        'payment-failed' => 'Zahlung fehlgeschlagen. Bitte versuchen Sie es erneut.',
        'payment-processing' => 'Ihre Zahlung wird bestätigt. Bitte prüfen Sie in Kürze Ihre Bestellungen.',
        'payment-success' => 'Zahlung erfolgreich abgeschlossen!',
        'provide-credentials' => 'Bitte konfigurieren Sie die PayTR Merchant-ID, den Merchant-Key und das Merchant-Salt im Admin-Bereich.',
        'token-request-failed' => 'PayTR-Zahlung konnte nicht gestartet werden. Bitte versuchen Sie es erneut.',
    ],
];
