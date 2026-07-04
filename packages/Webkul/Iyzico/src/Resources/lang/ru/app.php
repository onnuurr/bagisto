<?php

return [
    'description' => 'Оплачивайте безопасно кредитной/дебетовой картой через iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Перенаправление на iyzico...',
        'redirecting-to-payment' => 'Перенаправление на оплату iyzico',
        'secure-payment' => 'Безопасный платёжный шлюз',
    ],

    'response' => [
        'cart-not-found' => 'Корзина не найдена. Попробуйте ещё раз.',
        'hash-mismatch' => 'Проверка платежа не удалась. Обратитесь в поддержку.',
        'initialize-failed' => 'Не удалось начать оплату через iyzico. Попробуйте ещё раз.',
        'invalid-transaction' => 'Недействительная транзакция. Попробуйте ещё раз.',
        'order-creation-failed' => 'Не удалось создать заказ. Обратитесь в поддержку.',
        'payment-failed' => 'Платёж не удался. Попробуйте ещё раз.',
        'payment-success' => 'Оплата успешно завершена!',
        'provide-credentials' => 'Пожалуйста, настройте API Key и Secret Key iyzico в панели администратора.',
    ],
];
