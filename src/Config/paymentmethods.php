<?php

return [
    'mercadopago' => [
        'code'        => 'mercadopago',
        'title'       => 'Mercado Pago',
        'description' => 'Pague com Mercado Pago - Aceitamos Pix, Cartão de Crédito e Boleto',
        'class'       => 'Webkul\\MercadoPago\\Payment\\MercadoPago',
        'active'      => true,
        'sort'        => 3,
        'sandbox'     => true,
        'public_key'  => '',
        'access_token' => '',
        'webhook_url' => '',
        'webhook_secret' => '',
        'debug'       => false,
        'payment_methods' => [
            'pix' => [
                'enabled' => true,
                'expiration_minutes' => 30,
            ],
            'credit_card' => [
                'enabled' => true,
                'installments' => [
                    'min' => 1,
                    'max' => 12,
                    'interest_free' => 1,
                ],
            ],
            'boleto' => [
                'enabled' => true,
                'expiration_days' => 3,
            ],
        ],
    ],
];
