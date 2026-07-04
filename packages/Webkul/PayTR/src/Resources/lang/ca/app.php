<?php

return [
    'description' => 'Pagueu de forma segura amb targeta de crèdit/dèbit mitjançant PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Redirigint a PayTR...',
        'redirecting-to-payment' => 'Redirigint al pagament de PayTR',
        'secure-payment' => 'Passarel·la de pagament segura',
    ],

    'response' => [
        'cart-not-found' => 'No s\'ha trobat la cistella. Torneu-ho a provar.',
        'payment-failed' => 'El pagament ha fallat. Torneu-ho a provar.',
        'payment-processing' => 'El vostre pagament s\'està confirmant. Comproveu les vostres comandes en breu.',
        'payment-success' => 'Pagament completat correctament!',
        'provide-credentials' => 'Configureu l\'ID de comerciant, la clau de comerciant i la sal de comerciant de PayTR al tauler d\'administració.',
        'token-request-failed' => 'No s\'ha pogut iniciar el pagament de PayTR. Torneu-ho a provar.',
    ],
];
