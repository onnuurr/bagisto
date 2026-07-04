<?php

return [
    'description' => 'Płać bezpiecznie kartą kredytową/debetową za pośrednictwem PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Przekierowywanie do PayTR...',
        'redirecting-to-payment' => 'Przekierowywanie do płatności PayTR',
        'secure-payment' => 'Bezpieczna bramka płatności',
    ],

    'response' => [
        'cart-not-found' => 'Nie znaleziono koszyka. Spróbuj ponownie.',
        'payment-failed' => 'Płatność nie powiodła się. Spróbuj ponownie.',
        'payment-processing' => 'Twoja płatność jest potwierdzana. Sprawdź wkrótce swoje zamówienia.',
        'payment-success' => 'Płatność zakończona pomyślnie!',
        'provide-credentials' => 'Skonfiguruj identyfikator sprzedawcy, klucz sprzedawcy i sól sprzedawcy PayTR w panelu administracyjnym.',
        'token-request-failed' => 'Nie udało się rozpocząć płatności PayTR. Spróbuj ponownie.',
    ],
];
