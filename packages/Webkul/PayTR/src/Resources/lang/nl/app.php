<?php

return [
    'description' => 'Betaal veilig met credit-/betaalkaart via PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Doorverwijzen naar PayTR...',
        'redirecting-to-payment' => 'Doorverwijzen naar PayTR-betaling',
        'secure-payment' => 'Beveiligde betaalgateway',
    ],

    'response' => [
        'cart-not-found' => 'Winkelwagen niet gevonden. Probeer het opnieuw.',
        'payment-failed' => 'Betaling mislukt. Probeer het opnieuw.',
        'payment-processing' => 'Uw betaling wordt bevestigd. Controleer binnenkort uw bestellingen.',
        'payment-success' => 'Betaling succesvol voltooid!',
        'provide-credentials' => 'Configureer de PayTR Merchant ID, Merchant Key en Merchant Salt in het beheerpaneel.',
        'token-request-failed' => 'PayTR-betaling kon niet worden gestart. Probeer het opnieuw.',
    ],
];
