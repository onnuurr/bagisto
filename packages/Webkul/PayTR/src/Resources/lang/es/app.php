<?php

return [
    'description' => 'Pague de forma segura con tarjeta de crédito/débito a través de PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Redirigiendo a PayTR...',
        'redirecting-to-payment' => 'Redirigiendo al pago de PayTR',
        'secure-payment' => 'Pasarela de pago segura',
    ],

    'response' => [
        'cart-not-found' => 'Carrito no encontrado. Inténtelo de nuevo.',
        'payment-failed' => 'El pago ha fallado. Inténtelo de nuevo.',
        'payment-processing' => 'Su pago se está confirmando. Compruebe sus pedidos en breve.',
        'payment-success' => '¡Pago completado con éxito!',
        'provide-credentials' => 'Configure el ID de comercio, la clave de comercio y la sal de comercio de PayTR en el panel de administración.',
        'token-request-failed' => 'No se pudo iniciar el pago de PayTR. Inténtelo de nuevo.',
    ],
];
