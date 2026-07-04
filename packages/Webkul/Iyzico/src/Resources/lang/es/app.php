<?php

return [
    'description' => 'Pague de forma segura con tarjeta de crédito/débito a través de iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Redirigiendo a iyzico...',
        'redirecting-to-payment' => 'Redirigiendo al pago de iyzico',
        'secure-payment' => 'Pasarela de pago segura',
    ],

    'response' => [
        'cart-not-found' => 'Carrito no encontrado. Inténtelo de nuevo.',
        'hash-mismatch' => 'La verificación del pago falló. Contacte con soporte.',
        'initialize-failed' => 'No se pudo iniciar el pago de iyzico. Inténtelo de nuevo.',
        'invalid-transaction' => 'Transacción no válida. Inténtelo de nuevo.',
        'order-creation-failed' => 'No se pudo crear el pedido. Contacte con soporte.',
        'payment-failed' => 'El pago ha fallado. Inténtelo de nuevo.',
        'payment-success' => '¡Pago completado con éxito!',
        'provide-credentials' => 'Configure la clave de API y la clave secreta de iyzico en el panel de administración.',
    ],
];
