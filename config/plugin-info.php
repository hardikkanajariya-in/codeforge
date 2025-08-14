<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plugin Information
    |--------------------------------------------------------------------------
    |
    | This configuration file contains all the details about the plugin
    | that will be displayed throughout the documentation and UI.
    |
    */

    'name' => 'Filament Database Manager',
    'short_name' => 'Database Manager',
    'version' => '1.0.0',
    'description' => 'A comprehensive database management solution for FilamentPHP applications',

    /*
    |--------------------------------------------------------------------------
    | Author & Company Information
    |--------------------------------------------------------------------------
    */

    'author' => [
        'name' => 'Your Name',
        'email' => 'your-email@example.com',
        'website' => 'https://your-website.com',
        'company' => 'Your Company Name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugin Type & License
    |--------------------------------------------------------------------------
    */

    'type' => 'premium', // premium, free, commercial
    'license' => 'Commercial License',
    'license_url' => 'https://your-website.com/license',

    /*
    |--------------------------------------------------------------------------
    | Support Information
    |--------------------------------------------------------------------------
    */

    'support' => [
        'email' => 'support@your-website.com',
        'documentation' => 'https://your-website.com/docs',
        'website' => 'https://your-website.com',
        'purchase_url' => 'https://your-website.com/purchase',
        'changelog' => 'https://your-website.com/changelog',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Links
    |--------------------------------------------------------------------------
    */

    'social' => [
        'twitter' => 'https://twitter.com/your-handle',
        'github' => null, // Set to null for closed source
        'linkedin' => 'https://linkedin.com/in/your-profile',
        'youtube' => 'https://youtube.com/your-channel',
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentation Settings
    |--------------------------------------------------------------------------
    */

    'docs' => [
        'title' => 'Database Manager Documentation',
        'subtitle' => 'Premium FilamentPHP Database Management Solution',
        'logo_url' => null, // Add your logo URL here
        'favicon_url' => null, // Add your favicon URL here
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding Colors
    |--------------------------------------------------------------------------
    */

    'colors' => [
        'primary' => '#2563eb', // Blue-600
        'primary_dark' => '#1d4ed8', // Blue-700
        'primary_light' => '#3b82f6', // Blue-500
        'secondary' => '#64748b', // Slate-500
        'accent' => '#06b6d4', // Cyan-500
        'success' => '#10b981', // Emerald-500
        'warning' => '#f59e0b', // Amber-500
        'danger' => '#ef4444', // Red-500
    ],
];
