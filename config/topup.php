<?php

return [
    'providers' => [
        'default' => [
            'api_url' => env('TOPUP_API_URL'),
            'api_key' => env('TOPUP_API_KEY'),
            'api_secret' => env('TOPUP_API_SECRET'),
            'timeout' => 30,
            'retry_times' => 3,
        ],
        'vendor1' => [
            'api_url' => env('VENDOR1_API_URL'),
            'api_key' => env('VENDOR1_API_KEY'),
            'api_secret' => env('VENDOR1_API_SECRET'),
        ],
    ],
    
    'max_retry_attempts' => 5,
    'retry_delay' => [10, 30, 60, 120, 300], // seconds
];