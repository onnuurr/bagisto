<?php

return [
    'description' => 'iyzico経由でクレジット/デビットカードにより安全に支払う',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'iyzicoにリダイレクトしています...',
        'redirecting-to-payment' => 'iyzico決済にリダイレクトしています',
        'secure-payment' => '安全な決済ゲートウェイ',
    ],

    'response' => [
        'cart-not-found' => 'カートが見つかりません。もう一度お試しください。',
        'hash-mismatch' => '決済の検証に失敗しました。サポートにお問い合わせください。',
        'initialize-failed' => 'iyzico決済を開始できませんでした。もう一度お試しください。',
        'invalid-transaction' => '無効な取引です。もう一度お試しください。',
        'order-creation-failed' => '注文の作成に失敗しました。サポートにお問い合わせください。',
        'payment-failed' => '決済に失敗しました。もう一度お試しください。',
        'payment-success' => '決済が正常に完了しました!',
        'provide-credentials' => '管理パネルでiyzicoのAPIキーとシークレットキーを設定してください。',
    ],
];
