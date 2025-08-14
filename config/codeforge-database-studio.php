<?php

return [
    /*
    |--------------------------------------------------------------------------
    | License Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Anystack license for CodeForge Database Studio.
    | Get your license key from: https://anystack.sh/products/hkdevs-codeforge-database-studio
    |
    */
    'license_key' => env('CODEFORGE_LICENSE_KEY'),
    'fingerprint' => env('CODEFORGE_FINGERPRINT'),
    'license_validation' => [
        'enabled' => env('CODEFORGE_LICENSE_VALIDATION', true),
        'cache_duration' => 3600, // 1 hour
        'grace_period' => 7, // Days to allow usage after license expiry
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Registration
    |--------------------------------------------------------------------------
    |
    | Automatically register the plugin on specified panels.
    |
    */
    'auto_register' => true,

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | The panels where this plugin should be registered.
    |
    */
    'register_on_panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Configure the navigation settings for the plugin.
    |
    */
    'navigation' => [
        'group' => 'Database Studio',
        'sort' => 1,
        'icon' => 'heroicon-o-server',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific features of the plugin.
    | Navigation groups are automatically organized by individual pages/resources.
    |
    */
    'features' => [
        'schema_designer' => true,
        'migration_manager' => true,
        'health_monitoring' => true,
        'smart_seeding' => true,
        'documentation_generator' => true,
        'code_generation' => true,
        'dev_docs' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Specify which database connections should be managed.
    |
    */
    'connections' => [
        'default' => env('DB_CONNECTION', 'mysql'),
        'allowed' => ['mysql', 'pgsql', 'sqlite', 'sqlsrv'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Settings
    |--------------------------------------------------------------------------
    |
    | Configure migration management settings.
    |
    */
    'migrations' => [
        'track_history' => true,
        'backup_before_rollback' => true,
        'max_history_records' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Monitoring
    |--------------------------------------------------------------------------
    |
    | Configure database health monitoring settings.
    |
    */
    'health_monitoring' => [
        'enabled' => true,
        'check_interval' => 300, // 5 minutes
        'slow_query_threshold' => 1000, // milliseconds
        'connection_timeout' => 5, // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Performance Logging
    |--------------------------------------------------------------------------
    |
    | Configure automatic query performance logging.
    |
    */
    'enable_query_logging' => true,
    'query_logging' => [
        'slow_query_threshold' => 1000, // Log queries slower than this (ms)
        'log_all_queries' => false, // Set to true to log all queries
        'max_log_entries' => 10000, // Maximum number of log entries to keep
        'cleanup_older_than_days' => 30, // Clean up logs older than X days
        'skip_patterns' => [
            'show tables',
            'show columns',
            'information_schema',
            'query_performance_logs',
            'database_health_metrics',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Schema Designer
    |--------------------------------------------------------------------------
    |
    | Configure the visual schema designer.
    |
    */
    'schema_designer' => [
        'auto_save' => true,
        'auto_save_interval' => 30, // seconds
        'max_tables_per_diagram' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Code Generation
    |--------------------------------------------------------------------------
    |
    | Configure the code generation features.
    |
    */
    'code_generation' => [
        'output_path' => [
            'models' => 'app/Models',
            'migrations' => 'database/migrations',
            'factories' => 'database/factories',
            'seeders' => 'database/seeders',
            'resources' => 'app/Filament/Resources',
        ],
        'namespace' => [
            'models' => 'App\\Models',
            'factories' => 'Database\\Factories',
            'seeders' => 'Database\\Seeders',
            'resources' => 'App\\Filament\\Resources',
        ],
        'auto_format' => true,
        'backup_existing' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | Configure security settings.
    |
    */
    'security' => [
        'require_confirmation' => [
            'drop_table' => true,
            'drop_column' => true,
            'rollback_migration' => true,
        ],
        'allowed_operations' => [
            'create_table' => true,
            'alter_table' => true,
            'drop_table' => false, // Disabled by default for safety
            'create_migration' => true,
            'rollback_migration' => true,
        ],
    ],
];
