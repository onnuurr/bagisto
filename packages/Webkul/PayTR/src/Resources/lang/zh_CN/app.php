<?php

return [
    'description' => '通过 PayTR 使用信用卡/借记卡安全支付',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => '正在跳转到 PayTR...',
        'redirecting-to-payment' => '正在跳转到 PayTR 支付',
        'secure-payment' => '安全支付网关',
    ],

    'response' => [
        'cart-not-found' => '未找到购物车，请重试。',
        'payment-failed' => '支付失败，请重试。',
        'payment-processing' => '正在确认您的付款，请稍后查看您的订单。',
        'payment-success' => '支付成功完成！',
        'provide-credentials' => '请在管理面板中配置 PayTR 商户 ID、商户密钥和商户盐值。',
        'token-request-failed' => '无法启动 PayTR 支付，请重试。',
    ],
];
