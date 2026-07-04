<?php

return [
    'description' => 'ادفع بأمان باستخدام بطاقة الائتمان/الخصم عبر iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'جارٍ التحويل إلى iyzico...',
        'redirecting-to-payment' => 'جارٍ التحويل إلى الدفع عبر iyzico',
        'secure-payment' => 'بوابة دفع آمنة',
    ],

    'response' => [
        'cart-not-found' => 'لم يتم العثور على السلة. حاول مرة أخرى.',
        'hash-mismatch' => 'فشل التحقق من الدفع. يرجى الاتصال بالدعم.',
        'initialize-failed' => 'تعذر بدء الدفع عبر iyzico. حاول مرة أخرى.',
        'invalid-transaction' => 'معاملة غير صالحة. حاول مرة أخرى.',
        'order-creation-failed' => 'فشل إنشاء الطلب. يرجى الاتصال بالدعم.',
        'payment-failed' => 'فشلت عملية الدفع. حاول مرة أخرى.',
        'payment-success' => 'تم الدفع بنجاح!',
        'provide-credentials' => 'يرجى تكوين مفتاح API والمفتاح السري الخاص بـ iyzico في لوحة الإدارة.',
    ],
];
