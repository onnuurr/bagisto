<?php

return [
    'description' => 'Payez en toute sécurité par carte bancaire via iyzico',
    'title' => 'iyzico',

    'redirect' => [
        'redirecting' => 'Redirection vers iyzico...',
        'redirecting-to-payment' => 'Redirection vers le paiement iyzico',
        'secure-payment' => 'Passerelle de paiement sécurisée',
    ],

    'response' => [
        'cart-not-found' => 'Panier introuvable. Veuillez réessayer.',
        'hash-mismatch' => 'La vérification du paiement a échoué. Veuillez contacter le support.',
        'initialize-failed' => 'Impossible de démarrer le paiement iyzico. Veuillez réessayer.',
        'invalid-transaction' => 'Transaction invalide. Veuillez réessayer.',
        'order-creation-failed' => 'Impossible de créer la commande. Veuillez contacter le support.',
        'payment-failed' => 'Le paiement a échoué. Veuillez réessayer.',
        'payment-success' => 'Paiement effectué avec succès !',
        'provide-credentials' => 'Veuillez configurer la clé API et la clé secrète iyzico dans le panneau d\'administration.',
    ],
];
