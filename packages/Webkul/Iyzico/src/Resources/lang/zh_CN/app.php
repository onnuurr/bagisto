<?php

return [
    'description' => '通过 iyzico 使用信用卡/借记卡安全支付',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => '正在跳转到 iyzico...',
        'redirecting-to-payment' => '正在跳转到 iyzico 支付',
        'secure-payment' => '安全支付网关',
    ],

    'response' => [
        'cart-not-found' => '未找到购物车，请重试。',
        'hash-mismatch' => '支付验证失败，请联系支持人员。',
        'initialize-failed' => '无法启动 iyzico 支付，请重试。',
        'invalid-transaction' => '无效交易，请重试。',
        'order-creation-failed' => '创建订单失败，请联系支持人员。',
        'payment-failed' => '支付失败，请重试。',
        'payment-success' => '支付成功完成！',
        'provide-credentials' => '请在管理面板中配置 iyzico API 密钥和密钥。',
    ],
];
