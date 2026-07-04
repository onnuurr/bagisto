<?php

return [
    'description' => 'Paga in modo sicuro con carta di credito/debito tramite iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Reindirizzamento a iyzico...',
        'redirecting-to-payment' => 'Reindirizzamento al pagamento iyzico',
        'secure-payment' => 'Gateway di pagamento sicuro',
    ],

    'response' => [
        'cart-not-found' => 'Carrello non trovato. Riprova.',
        'hash-mismatch' => 'Verifica del pagamento non riuscita. Contatta l\'assistenza.',
        'initialize-failed' => 'Impossibile avviare il pagamento iyzico. Riprova.',
        'invalid-transaction' => 'Transazione non valida. Riprova.',
        'order-creation-failed' => 'Impossibile creare l\'ordine. Contatta l\'assistenza.',
        'payment-failed' => 'Pagamento non riuscito. Riprova.',
        'payment-success' => 'Pagamento completato con successo!',
        'provide-credentials' => 'Configura la API Key e la Secret Key di iyzico nel pannello di amministrazione.',
    ],
];
