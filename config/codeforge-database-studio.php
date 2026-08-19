<?php

/*
|--------------------------------------------------------------------------
| CodeForge Database Studio configuration
|--------------------------------------------------------------------------
|
| Keys marked [ACTIVE] are read at runtime by the package.
| Keys marked [RESERVED] are published for future use; changing them today
| has no effect on plugin registration or feature toggles.
|
| Register Filament pages/resources via CodeForgeStudioPlugin::make()->enable*()
| in your panel provider—not via this config file.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Registration [RESERVED]
    |--------------------------------------------------------------------------
    */
    'auto_register' => true,

    /*
    |--------------------------------------------------------------------------
    | Panels [RESERVED]
    |--------------------------------------------------------------------------
    */
    'register_on_panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Navigation [ACTIVE: sort offset for some resources]
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Database Studio',
        'sort' => 1,
        'icon' => 'heroicon-o-server',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features [ACTIVE: Database Overview quick-action cards only]
    |--------------------------------------------------------------------------
    |
    | Does not register or unregister Filament pages. Use enable*() on the plugin.
    | dev_docs here only affects the overview blade fallback when enableDevDocs()
    | is not set on the plugin (plugin default: disabled).
    |
    */
    'features' => [
        'schema_designer' => true,
        'migration_manager' => true,
        'health_monitoring' => true,
        'smart_seeding' => true,
        'documentation_generator' => true,
        'code_generation' => true,
        'dev_docs' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connections [RESERVED]
    |--------------------------------------------------------------------------
    */
    'connections' => [
        'default' => env('DB_CONNECTION', 'mysql'),
        'allowed' => ['mysql', 'pgsql', 'sqlite', 'sqlsrv'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Settings [RESERVED]
    |--------------------------------------------------------------------------
    */
    'migrations' => [
        'track_history' => true,
        'backup_before_rollback' => true,
        'max_history_records' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Monitoring [RESERVED]
    |--------------------------------------------------------------------------
    */
    'health_monitoring' => [
        'enabled' => true,
        'check_interval' => 300, // 5 minutes
        'slow_query_threshold' => 1000, // milliseconds
        'connection_timeout' => 5, // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Performance Logging [ACTIVE]
    |--------------------------------------------------------------------------
    |
    | Read by QueryPerformanceListener. Toggle at runtime with
    | php artisan codeforge:toggle-query-logging
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
    | Schema Designer [RESERVED]
    |--------------------------------------------------------------------------
    */
    'schema_designer' => [
        'auto_save' => true,
        'auto_save_interval' => 30, // seconds
        'max_tables_per_diagram' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Code Generation [RESERVED]
    |--------------------------------------------------------------------------
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
    | Security [RESERVED]
    |--------------------------------------------------------------------------
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
