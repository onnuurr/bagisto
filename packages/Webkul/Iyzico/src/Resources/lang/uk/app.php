<?php

return [
    'description' => 'Оплачуйте безпечно кредитною/дебетовою карткою через iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Перенаправлення на iyzico...',
        'redirecting-to-payment' => 'Перенаправлення на оплату iyzico',
        'secure-payment' => 'Безпечний платіжний шлюз',
    ],

    'response' => [
        'cart-not-found' => 'Кошик не знайдено. Спробуйте ще раз.',
        'hash-mismatch' => 'Перевірка платежу не вдалася. Зверніться до підтримки.',
        'initialize-failed' => 'Не вдалося розпочати оплату iyzico. Спробуйте ще раз.',
        'invalid-transaction' => 'Недійсна транзакція. Спробуйте ще раз.',
        'order-creation-failed' => 'Не вдалося створити замовлення. Зверніться до підтримки.',
        'payment-failed' => 'Оплата не вдалася. Спробуйте ще раз.',
        'payment-success' => 'Оплату успішно завершено!',
        'provide-credentials' => 'Будь ласка, налаштуйте API Key та Secret Key iyzico у панелі адміністратора.',
    ],
];
