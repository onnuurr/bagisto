<?php

return [
    'description' => 'Payez en toute sécurité par carte bancaire via PayTR',
    'title' => 'PayTR',

    'redirect' => [
        'redirecting' => 'Redirection vers PayTR...',
        'redirecting-to-payment' => 'Redirection vers le paiement PayTR',
        'secure-payment' => 'Passerelle de paiement sécurisée',
    ],

    'response' => [
        'cart-not-found' => 'Panier introuvable. Veuillez réessayer.',
        'payment-failed' => 'Le paiement a échoué. Veuillez réessayer.',
        'payment-processing' => 'Votre paiement est en cours de confirmation. Veuillez vérifier vos commandes sous peu.',
        'payment-success' => 'Paiement effectué avec succès !',
        'provide-credentials' => 'Veuillez configurer l\'ID marchand, la clé marchand et le sel marchand PayTR dans le panneau d\'administration.',
        'token-request-failed' => 'Impossible de démarrer le paiement PayTR. Veuillez réessayer.',
    ],
];
