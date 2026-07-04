<?php

return [
    'description' => 'Оплачуйте безпечно кредитною/дебетовою карткою через PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Перенаправлення на PayTR...',
        'redirecting-to-payment' => 'Перенаправлення на оплату PayTR',
        'secure-payment' => 'Безпечний платіжний шлюз',
    ],

    'response' => [
        'cart-not-found' => 'Кошик не знайдено. Спробуйте ще раз.',
        'payment-failed' => 'Оплата не вдалася. Спробуйте ще раз.',
        'payment-processing' => 'Ваш платіж підтверджується. Незабаром перевірте свої замовлення.',
        'payment-success' => 'Оплату успішно завершено!',
        'provide-credentials' => 'Будь ласка, налаштуйте Merchant ID, Merchant Key та Merchant Salt PayTR у панелі адміністратора.',
        'token-request-failed' => 'Не вдалося розпочати оплату PayTR. Спробуйте ще раз.',
    ],
];
