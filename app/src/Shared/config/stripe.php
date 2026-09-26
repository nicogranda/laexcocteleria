<?php
require_once __DIR__ . '/env.php';

return [
    'stripe' => [
        'public_key' => $_ENV['STRIPE_PUBLIC_KEY'],
        'secret_key' => $_ENV['STRIPE_SECRET_KEY'],
        'currency'   => 'usd'
    ]
];