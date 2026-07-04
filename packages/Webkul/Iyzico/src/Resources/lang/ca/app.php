<?php

return [
    'description' => 'Pagueu de forma segura amb targeta de crèdit/dèbit mitjançant iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Redirigint a iyzico...',
        'redirecting-to-payment' => 'Redirigint al pagament d\'iyzico',
        'secure-payment' => 'Passarel·la de pagament segura',
    ],

    'response' => [
        'cart-not-found' => 'No s\'ha trobat la cistella. Torneu-ho a provar.',
        'hash-mismatch' => 'La verificació del pagament ha fallat. Contacteu amb el suport.',
        'initialize-failed' => 'No s\'ha pogut iniciar el pagament d\'iyzico. Torneu-ho a provar.',
        'invalid-transaction' => 'Transacció no vàlida. Torneu-ho a provar.',
        'order-creation-failed' => 'No s\'ha pogut crear la comanda. Contacteu amb el suport.',
        'payment-failed' => 'El pagament ha fallat. Torneu-ho a provar.',
        'payment-success' => 'Pagament completat correctament!',
        'provide-credentials' => 'Configureu la clau API i la clau secreta d\'iyzico al tauler d\'administració.',
    ],
];
