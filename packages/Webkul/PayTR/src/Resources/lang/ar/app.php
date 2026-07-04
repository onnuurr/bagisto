<?php

return [
    'description' => 'ادفع بأمان باستخدام بطاقة الائتمان/الخصم عبر PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'جارٍ التحويل إلى PayTR...',
        'redirecting-to-payment' => 'جارٍ التحويل إلى الدفع عبر PayTR',
        'secure-payment' => 'بوابة دفع آمنة',
    ],

    'response' => [
        'cart-not-found' => 'لم يتم العثور على السلة. حاول مرة أخرى.',
        'payment-failed' => 'فشلت عملية الدفع. حاول مرة أخرى.',
        'payment-processing' => 'جارٍ تأكيد دفعتك. يرجى التحقق من طلباتك قريبًا.',
        'payment-success' => 'تم الدفع بنجاح!',
        'provide-credentials' => 'يرجى تكوين رقم التاجر ومفتاح التاجر وسولت التاجر الخاص بـ PayTR في لوحة الإدارة.',
        'token-request-failed' => 'تعذر بدء الدفع عبر PayTR. حاول مرة أخرى.',
    ],
];
