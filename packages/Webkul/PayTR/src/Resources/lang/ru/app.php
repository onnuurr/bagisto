<?php

return [
    'description' => 'Оплачивайте безопасно кредитной/дебетовой картой через PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Перенаправление на PayTR...',
        'redirecting-to-payment' => 'Перенаправление на оплату PayTR',
        'secure-payment' => 'Безопасный платёжный шлюз',
    ],

    'response' => [
        'cart-not-found' => 'Корзина не найдена. Попробуйте ещё раз.',
        'payment-failed' => 'Платёж не удался. Попробуйте ещё раз.',
        'payment-processing' => 'Ваш платёж подтверждается. Пожалуйста, скоро проверьте свои заказы.',
        'payment-success' => 'Оплата успешно завершена!',
        'provide-credentials' => 'Пожалуйста, настройте Merchant ID, Merchant Key и Merchant Salt PayTR в панели администратора.',
        'token-request-failed' => 'Не удалось начать оплату через PayTR. Попробуйте ещё раз.',
    ],
];
