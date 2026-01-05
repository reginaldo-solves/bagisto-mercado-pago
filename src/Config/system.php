<?php

return [
    [
        'key'    => 'sales.payment_methods.mercadopago',
        'name'   => 'mercadopago::app.admin.system.mercadopago',
        'info'   => 'mercadopago::app.admin.system.mercadopago-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'mercadopago::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'title',
                'title'         => 'mercadopago::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'mercadopago::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'public_key',
                'title'         => 'mercadopago::app.admin.system.public-key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'access_token',
                'title'         => 'mercadopago::app.admin.system.access-token',
                'type'          => 'password',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'sandbox',
                'title'         => 'mercadopago::app.admin.system.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'webhook_url',
                'title'         => 'mercadopago::app.admin.system.webhook-url',
                'type'          => 'text',
                'info'          => 'mercadopago::app.admin.system.webhook-url-info',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'sort',
                'title'         => 'mercadopago::app.admin.system.sort_order',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ],
                    [
                        'title' => '2',
                        'value' => 2,
                    ],
                    [
                        'title' => '3',
                        'value' => 3,
                    ],
                    [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ]
        ],
    ]
];
