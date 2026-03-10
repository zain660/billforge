<?php

return [
    'route_prefix' => 'admin/billforge',

    'middleware' => ['web', 'auth'], // Middleware for the admin dashboard

    'gateways' => [
        'stripe' => [
            'class' => \Zain\BillForge\Gateways\StripeGateway::class ,
        ],
        'paypal' => [
            'class' => \Zain\BillForge\Gateways\PaypalGateway::class ,
        ],
        'authorize_net' => [
            'class' => \Zain\BillForge\Gateways\AuthorizeNetGateway::class ,
        ],
    ],
];



