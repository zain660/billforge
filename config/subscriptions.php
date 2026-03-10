<?php

return [
    'route_prefix' => 'admin/subscriptions',

    'middleware' => ['web', 'auth'], // Middleware for the admin dashboard

    'gateways' => [
        'stripe' => [
            'class' => \Zain\LaravelSubscriptions\Gateways\StripeGateway::class ,
        ],
        'paypal' => [
            'class' => \Zain\LaravelSubscriptions\Gateways\PaypalGateway::class ,
        ],
        'authorize_net' => [
            'class' => \Zain\LaravelSubscriptions\Gateways\AuthorizeNetGateway::class ,
        ],
    ],
];
