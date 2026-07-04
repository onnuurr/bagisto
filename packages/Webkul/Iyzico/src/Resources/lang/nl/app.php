<?php

return [
    'description' => 'Betaal veilig met credit-/betaalkaart via iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Doorverwijzen naar iyzico...',
        'redirecting-to-payment' => 'Doorverwijzen naar iyzico-betaling',
        'secure-payment' => 'Beveiligde betaalgateway',
    ],

    'response' => [
        'cart-not-found' => 'Winkelwagen niet gevonden. Probeer het opnieuw.',
        'hash-mismatch' => 'Betalingsverificatie mislukt. Neem contact op met support.',
        'initialize-failed' => 'iyzico-betaling kon niet worden gestart. Probeer het opnieuw.',
        'invalid-transaction' => 'Ongeldige transactie. Probeer het opnieuw.',
        'order-creation-failed' => 'Bestelling kon niet worden aangemaakt. Neem contact op met support.',
        'payment-failed' => 'Betaling mislukt. Probeer het opnieuw.',
        'payment-success' => 'Betaling succesvol voltooid!',
        'provide-credentials' => 'Configureer de iyzico API Key en Secret Key in het beheerpaneel.',
    ],
];
