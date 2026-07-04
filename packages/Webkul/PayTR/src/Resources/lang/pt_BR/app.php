<?php

return [
    'description' => 'Pague com segurança usando cartão de crédito/débito via PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Redirecionando para o PayTR...',
        'redirecting-to-payment' => 'Redirecionando para o pagamento PayTR',
        'secure-payment' => 'Gateway de Pagamento Seguro',
    ],

    'response' => [
        'cart-not-found' => 'Carrinho não encontrado. Tente novamente.',
        'payment-failed' => 'Falha no pagamento. Tente novamente.',
        'payment-processing' => 'Seu pagamento está sendo confirmado. Verifique seus pedidos em breve.',
        'payment-success' => 'Pagamento concluído com sucesso!',
        'provide-credentials' => 'Configure o ID do comerciante, a chave do comerciante e o salt do comerciante do PayTR no painel administrativo.',
        'token-request-failed' => 'Não foi possível iniciar o pagamento PayTR. Tente novamente.',
    ],
];
