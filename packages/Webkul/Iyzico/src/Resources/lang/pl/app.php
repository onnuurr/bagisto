<?php

return [
    'description' => 'Płać bezpiecznie kartą kredytową/debetową za pośrednictwem iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Przekierowywanie do iyzico...',
        'redirecting-to-payment' => 'Przekierowywanie do płatności iyzico',
        'secure-payment' => 'Bezpieczna bramka płatności',
    ],

    'response' => [
        'cart-not-found' => 'Nie znaleziono koszyka. Spróbuj ponownie.',
        'hash-mismatch' => 'Weryfikacja płatności nie powiodła się. Skontaktuj się z pomocą techniczną.',
        'initialize-failed' => 'Nie udało się rozpocząć płatności iyzico. Spróbuj ponownie.',
        'invalid-transaction' => 'Nieprawidłowa transakcja. Spróbuj ponownie.',
        'order-creation-failed' => 'Nie udało się utworzyć zamówienia. Skontaktuj się z pomocą techniczną.',
        'payment-failed' => 'Płatność nie powiodła się. Spróbuj ponownie.',
        'payment-success' => 'Płatność zakończona pomyślnie!',
        'provide-credentials' => 'Skonfiguruj klucz API i klucz tajny iyzico w panelu administracyjnym.',
    ],
];
