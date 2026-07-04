<?php

return [
    'description' => 'PayTR経由でクレジット/デビットカードにより安全に支払う',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'PayTRにリダイレクトしています...',
        'redirecting-to-payment' => 'PayTR決済にリダイレクトしています',
        'secure-payment' => '安全な決済ゲートウェイ',
    ],

    'response' => [
        'cart-not-found' => 'カートが見つかりません。もう一度お試しください。',
        'payment-failed' => '決済に失敗しました。もう一度お試しください。',
        'payment-processing' => 'お支払いを確認しています。まもなくご注文をご確認ください。',
        'payment-success' => '決済が正常に完了しました!',
        'provide-credentials' => '管理パネルでPayTRのMerchant ID、Merchant Key、Merchant Saltを設定してください。',
        'token-request-failed' => 'PayTR決済を開始できませんでした。もう一度お試しください。',
    ],
];
