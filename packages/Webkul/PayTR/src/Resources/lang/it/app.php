<?php

return [
    'description' => 'Paga in modo sicuro con carta di credito/debito tramite PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Reindirizzamento a PayTR...',
        'redirecting-to-payment' => 'Reindirizzamento al pagamento PayTR',
        'secure-payment' => 'Gateway di pagamento sicuro',
    ],

    'response' => [
        'cart-not-found' => 'Carrello non trovato. Riprova.',
        'payment-failed' => 'Pagamento non riuscito. Riprova.',
        'payment-processing' => 'Il tuo pagamento è in fase di conferma. Controlla i tuoi ordini a breve.',
        'payment-success' => 'Pagamento completato con successo!',
        'provide-credentials' => 'Configura l\'ID commerciante, la chiave commerciante e il salt commerciante di PayTR nel pannello di amministrazione.',
        'token-request-failed' => 'Impossibile avviare il pagamento PayTR. Riprova.',
    ],
];
