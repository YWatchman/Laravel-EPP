<?php

/*
 * EPP Connection settings
 */

return [
    'registrars' => [
        'sidn' => [
            'username' => env('SIDN_USERNAME'),
            'password' => env('SIDN_PASSWORD'),
            'hostname' => env('SIDN_HOSTNAME'),
            'port'     => env('SIDN_PORT', 700),
            'timeout'  => env('SIDN_TIMEOUT', 30),
        ],
    ],
    'debug' => env('EPP_DEBUG', false),
];
