<?php

return [
    'description' => 'Sicher mit Kredit-/Debitkarte über iyzico bezahlen',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Weiterleitung zu iyzico...',
        'redirecting-to-payment' => 'Weiterleitung zur iyzico-Zahlung',
        'secure-payment' => 'Sichere Zahlungsgateway',
    ],

    'response' => [
        'cart-not-found' => 'Warenkorb nicht gefunden. Bitte versuchen Sie es erneut.',
        'hash-mismatch' => 'Zahlungsverifizierung fehlgeschlagen. Bitte kontaktieren Sie den Support.',
        'initialize-failed' => 'iyzico-Zahlung konnte nicht gestartet werden. Bitte versuchen Sie es erneut.',
        'invalid-transaction' => 'Ungültige Transaktion. Bitte versuchen Sie es erneut.',
        'order-creation-failed' => 'Bestellung konnte nicht erstellt werden. Bitte kontaktieren Sie den Support.',
        'payment-failed' => 'Zahlung fehlgeschlagen. Bitte versuchen Sie es erneut.',
        'payment-success' => 'Zahlung erfolgreich abgeschlossen!',
        'provide-credentials' => 'Bitte konfigurieren Sie den iyzico API-Schlüssel und den geheimen Schlüssel im Admin-Bereich.',
    ],
];
