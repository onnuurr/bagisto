<?php

return [
    'description' => 'iyzico এর মাধ্যমে নিরাপদে ক্রেডিট/ডেবিট কার্ডে পেমেন্ট করুন',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'iyzico-এ পুনঃনির্দেশিত হচ্ছে...',
        'redirecting-to-payment' => 'iyzico পেমেন্টে পুনঃনির্দেশিত হচ্ছে',
        'secure-payment' => 'নিরাপদ পেমেন্ট গেটওয়ে',
    ],

    'response' => [
        'cart-not-found' => 'কার্ট পাওয়া যায়নি। আবার চেষ্টা করুন।',
        'hash-mismatch' => 'পেমেন্ট যাচাই ব্যর্থ হয়েছে। সহায়তার সাথে যোগাযোগ করুন।',
        'initialize-failed' => 'iyzico পেমেন্ট শুরু করা যায়নি। আবার চেষ্টা করুন।',
        'invalid-transaction' => 'অবৈধ লেনদেন। আবার চেষ্টা করুন।',
        'order-creation-failed' => 'অর্ডার তৈরি করা যায়নি। সহায়তার সাথে যোগাযোগ করুন।',
        'payment-failed' => 'পেমেন্ট ব্যর্থ হয়েছে। আবার চেষ্টা করুন।',
        'payment-success' => 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে!',
        'provide-credentials' => 'অনুগ্রহ করে অ্যাডমিন প্যানেলে iyzico API কী এবং সিক্রেট কী কনফিগার করুন।',
    ],
];
