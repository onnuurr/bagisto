<?php

return [
    'description' => 'שלמו בבטחה בכרטיס אשראי/חיוב דרך iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'מפנה אל iyzico...',
        'redirecting-to-payment' => 'מפנה לתשלום iyzico',
        'secure-payment' => 'שער תשלום מאובטח',
    ],

    'response' => [
        'cart-not-found' => 'העגלה לא נמצאה. נסו שוב.',
        'hash-mismatch' => 'אימות התשלום נכשל. אנא פנו לתמיכה.',
        'initialize-failed' => 'לא ניתן היה להתחיל את תשלום iyzico. נסו שוב.',
        'invalid-transaction' => 'עסקה לא חוקית. נסו שוב.',
        'order-creation-failed' => 'יצירת ההזמנה נכשלה. אנא פנו לתמיכה.',
        'payment-failed' => 'התשלום נכשל. נסו שוב.',
        'payment-success' => 'התשלום הושלם בהצלחה!',
        'provide-credentials' => 'אנא הגדירו את מפתח ה-API והמפתח הסודי של iyzico בפאנל הניהול.',
    ],
];
