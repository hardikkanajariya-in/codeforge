<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Anystack Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Anystack.sh integration for commercial distribution
    |
    */

    'enabled' => env('ANYSTACK_ENABLED', true),

    'product_id' => env('ANYSTACK_PRODUCT_ID', 'YOUR_ANYSTACK_PRODUCT_ID'),

    'webhook_secret' => env('ANYSTACK_WEBHOOK_SECRET'),

    'api_key' => env('ANYSTACK_API_KEY'),

    'pricing' => [
        'single' => [
            'price' => 79.00,
            'currency' => 'EUR',
            'description' => 'Single Project License - Use on one Laravel project',
            'features' => [
                'Full database management suite',
                'Schema designer',
                'Migration tools',
                'Code generation',
                '1 year of updates',
                'Email support'
            ]
        ],
        'multiple' => [
            'price' => 129.00,
            'currency' => 'EUR',
            'description' => 'Multiple Project License - Use on up to 5 Laravel projects',
            'features' => [
                'Everything in Single License',
                'Use on up to 5 projects',
                'Priority email support',
                'Advanced documentation',
                '1 year of updates'
            ]
        ],
        'unlimited' => [
            'price' => 199.00,
            'currency' => 'EUR',
            'description' => 'Unlimited License - Use on unlimited Laravel projects',
            'features' => [
                'Everything in Multiple License',
                'Unlimited projects',
                'Priority support with 24h response',
                'Access to private Discord channel',
                'Lifetime updates',
                'Custom feature requests'
            ]
        ]
    ],

    'github' => [
        'repository' => 'hardik-kanajariya/codeforge',
        'release_webhook' => env('ANYSTACK_GITHUB_WEBHOOK_URL'),
        'auto_sync_releases' => true
    ],

    'license_validation' => [
        'endpoint' => 'https://api.anystack.sh/v1/licenses/validate',
        'cache_duration' => 3600, // 1 hour
    ]
];
