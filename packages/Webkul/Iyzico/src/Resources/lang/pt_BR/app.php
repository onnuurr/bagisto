<?php

return [
    'description' => 'Pague com segurança usando cartão de crédito/débito via iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Redirecionando para o iyzico...',
        'redirecting-to-payment' => 'Redirecionando para o pagamento iyzico',
        'secure-payment' => 'Gateway de Pagamento Seguro',
    ],

    'response' => [
        'cart-not-found' => 'Carrinho não encontrado. Tente novamente.',
        'hash-mismatch' => 'Falha na verificação do pagamento. Entre em contato com o suporte.',
        'initialize-failed' => 'Não foi possível iniciar o pagamento iyzico. Tente novamente.',
        'invalid-transaction' => 'Transação inválida. Tente novamente.',
        'order-creation-failed' => 'Falha ao criar o pedido. Entre em contato com o suporte.',
        'payment-failed' => 'Falha no pagamento. Tente novamente.',
        'payment-success' => 'Pagamento concluído com sucesso!',
        'provide-credentials' => 'Configure a Chave de API e a Chave Secreta do iyzico no painel administrativo.',
    ],
];
