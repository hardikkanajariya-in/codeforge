@extends('codeforge-studio::layout.docs')

@section('title', 'Deployment - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>Advanced</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Deployment</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Production Deployment</h1>
                    <p class="text-xl text-gray-600">Deploy CodeForge Database Studio safely and efficiently to production
                        environments</p>
                </div>
            </div>
        </div>

        <!-- Deployment Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Deployment Architecture</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio requires careful consideration for production deployment
                including security configuration, performance optimization, and monitoring setup.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Security First</h3>
                    </div>
                    <p class="text-sm text-gray-600">Secure configurations, access control, and production-ready
                        security settings with confirmation requirements.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Performance Optimized</h3>
                    </div>
                    <p class="text-sm text-gray-600">Production-tuned configurations for monitoring, caching, and resource
                        management with minimal overhead.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Monitoring Ready</h3>
                    </div>
                    <p class="text-sm text-gray-600">Production monitoring with health checks, performance tracking, and
                        automated alerting systems.</p>
                </div>
            </div>
        </div>

        <!-- Prerequisites -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Production Prerequisites</h2>

            <div class="space-y-6">
                <!-- Authorization -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Authorization Requirements</h3>
                    <p class="text-gray-600 mb-3">Restrict database management features to trusted admin users in production:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code># Use Laravel policies and Filament authorization
Gate::define('manage-database', fn ($user) => $user->hasRole('admin'));

# Review destructive operation settings in config
'security' => [
    'require_confirmation' => [
        'drop_table' => true,
        'rollback_migration' => true,
    ],
    'allowed_operations' => [
        'drop_table' => false,
    ],
],</code></pre>
                    </div>
                </div>

                <!-- System Requirements -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">System Requirements</h3>
                    <p class="text-gray-600 mb-3">Production server requirements:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Server Requirements</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• PHP 8.1+ with required extensions</li>
                                <li>• Laravel 10.x or 11.x</li>
                                <li>• MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.35+</li>
                                <li>• Redis for caching (recommended)</li>
                                <li>• Minimum 512MB RAM for monitoring</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">PHP Extensions</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• PDO with database driver</li>
                                <li>• JSON extension</li>
                                <li>• Ctype extension</li>
                                <li>• Tokenizer extension</li>
                                <li>• OpenSSL extension</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Database Setup -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Configuration</h3>
                    <p class="text-gray-600 mb-3">Production database setup for CodeForge tables:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code># Production database connection
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_production_db
    DB_USERNAME=your_db_user
    DB_PASSWORD=your_secure_password

    # CodeForge specific database settings
    CODEFORGE_DB_CONNECTION=mysql  # Or separate connection
    CODEFORGE_ENABLE_QUERY_LOGGING=true
    CODEFORGE_LOG_SLOW_QUERIES=true
    CODEFORGE_SLOW_QUERY_THRESHOLD=1000</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installation Process -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Production Installation</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Step-by-Step Installation</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># 1. Install via Composer
    composer require hkdevs/codeforge-database-studio

    # 2. Publish configuration
    php artisan vendor:publish --tag=codeforge-config

    # 3. Configure environment variables
    # Add production settings to .env

    # 4. Run installation command
    php artisan codeforge:install --env=production

    # 5. Run migrations
    php artisan migrate

    # 6. Optimize for production
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    # 7. Verify installation
    php artisan codeforge:health:check</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Configuration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># config/codeforge-database-studio.php - Production settings
    return [
        // Enable only necessary features
        'features' => [
            'schema_designer' => false, // Disable in production for security
            'migration_manager' => true, // Allow with confirmations
            'health_monitoring' => true, // Essential for production
            'smart_seeding' => false, // Disable data generation in production
            'documentation_generator' => false, // Disable unless needed
            'code_generation' => false, // Disable in production
        ],

        // Security settings
        'security' => [
            'require_confirmation' => [
                'drop_table' => true,
                'drop_column' => true,
                'rollback_migration' => true,
            ],
            'allowed_operations' => [
                'create_table' => false, // Restrict schema changes
                'alter_table' => false,
                'drop_table' => false,
                'create_migration' => false,
                'rollback_migration' => true, // Allow rollbacks with confirmation
            ],
        ],

        // Performance settings
        'health_monitoring' => [
            'enabled' => true,
            'check_interval' => 600, // 10 minutes for production
            'slow_query_threshold' => 2000, // 2 seconds
            'connection_timeout' => 10,
            'metrics_retention_days' => 90,
        ],
    ];</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Environment Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Environment-Specific Configuration</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Environment Variables</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># .env - Production configuration
    APP_ENV=production
    APP_DEBUG=false

    # Security settings
    CODEFORGE_ALLOW_SCHEMA_CHANGES=false
    CODEFORGE_ALLOW_DATA_GENERATION=false
    CODEFORGE_REQUIRE_CONFIRMATIONS=true

    # Performance settings
    CODEFORGE_HEALTH_MONITORING=true
    CODEFORGE_QUERY_LOGGING=true
    CODEFORGE_CACHE_METRICS=true
    CODEFORGE_MONITORING_INTERVAL=600

    # Database settings
    CODEFORGE_SLOW_QUERY_THRESHOLD=2000
    CODEFORGE_CONNECTION_TIMEOUT=10
    CODEFORGE_METRICS_RETENTION=90

    # Feature toggles
    CODEFORGE_ENABLE_SCHEMA_DESIGNER=false
    CODEFORGE_ENABLE_CODE_GENERATION=false
    CODEFORGE_ENABLE_SMART_SEEDING=false
    CODEFORGE_ENABLE_HEALTH_MONITORING=true
    CODEFORGE_ENABLE_MIGRATION_MANAGER=true</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Staging Environment</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># .env - Staging configuration (for testing deployments)
    APP_ENV=staging
    APP_DEBUG=true

    # Security settings (slightly relaxed for testing)
    CODEFORGE_ALLOW_SCHEMA_CHANGES=true
    CODEFORGE_ALLOW_DATA_GENERATION=true
    CODEFORGE_REQUIRE_CONFIRMATIONS=true

    # Enable more features for testing
    CODEFORGE_ENABLE_SCHEMA_DESIGNER=true
    CODEFORGE_ENABLE_CODE_GENERATION=true
    CODEFORGE_ENABLE_SMART_SEEDING=true

    # Performance monitoring
    CODEFORGE_HEALTH_MONITORING=true
    CODEFORGE_MONITORING_INTERVAL=300  # More frequent for testing</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Considerations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Production Security</h2>

            <div class="space-y-6">
                <!-- Access Control -->
                <div class="border-l-4 border-red-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Access Control</h3>
                    <p class="text-gray-600 mb-3">Secure access to CodeForge functionality:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// In Filament AdminPanelProvider
    use Filament\Panel;
    use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->authMiddleware([
                \App\Http\Middleware\AdminOnly::class, // Custom middleware
            ])
            ->plugins([
                CodeForgeStudioPlugin::make()
                    ->restrictToRoles(['admin', 'developer']) // Limit access
                    ->requirePermissions(['manage-database']) // Check permissions
                    ->disableDangerousFeatures() // Disable schema changes
            ]);
    }

    // Custom middleware for additional security
    class AdminOnly
    {
        public function handle($request, Closure $next)
        {
            if (!auth()->user()?->isAdmin()) {
                abort(403, 'Access denied');
            }

            return $next($request);
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Feature Restrictions -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Feature Restrictions</h3>
                    <p class="text-gray-600 mb-3">Disable dangerous features in production:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// Production security configuration
    'security' => [
        'production_mode' => env('APP_ENV') === 'production',

        'require_confirmation' => [
            'drop_table' => true,
            'drop_column' => true,
            'rollback_migration' => true,
            'truncate_table' => true,
            'delete_records' => true,
        ],

        'allowed_operations' => [
            'create_table' => false, // Prevent schema changes
            'alter_table' => false,
            'drop_table' => false,
            'create_migration' => false,
            'generate_code' => false, // No code generation
            'seed_data' => false, // No data seeding
            'view_data' => true, // Allow viewing only
            'monitor_health' => true, // Allow monitoring
        ],

        'ip_whitelist' => [
            // '192.168.1.100', // Restrict by IP if needed
        ],

        'rate_limiting' => [
            'enabled' => true,
            'max_requests_per_minute' => 60,
        ],
    ],</code></pre>
                    </div>
                </div>

                <!-- Audit Logging -->
                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Audit Logging</h3>
                    <p class="text-gray-600 mb-3">Track all CodeForge operations in production:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// Enable comprehensive audit logging
    'audit_logging' => [
        'enabled' => true,
        'log_all_operations' => true,
        'include_user_info' => true,
        'include_ip_address' => true,
        'log_query_details' => true,
        'retention_days' => 365, // Keep logs for compliance

        'log_channels' => [
            'codeforge-audit', // Custom log channel
            'slack', // Slack notifications for critical operations
        ],

        'alert_on_operations' => [
            'schema_changes',
            'data_modifications',
            'configuration_changes',
        ],
    ],</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring & Maintenance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Production Monitoring</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Health Monitoring Setup</h3>
                    <p class="text-gray-600 mb-3">Configure comprehensive monitoring for production:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Set up monitoring cron job
    # Add to crontab (crontab -e)
    */10 * * * * php /path/to/your/app/artisan codeforge:health:monitor >> /dev/null 2>&1

    # Health check command
    php artisan codeforge:health:check --format=json

    # Monitor specific metrics
    php artisan codeforge:metrics:collect --connections=mysql,redis

    # Generate health report
    php artisan codeforge:health:report --email=admin@yourapp.com</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Automated Maintenance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Daily Maintenance</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><code>codeforge:cleanup:old-logs</code> - Clean old logs</div>
                                <div><code>codeforge:health:report</code> - Generate health report</div>
                                <div><code>codeforge:metrics:aggregate</code> - Aggregate metrics</div>
                                <div><code>codeforge:health:check</code> - Run health check</div>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Weekly Maintenance</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><code>codeforge:analyze:performance</code> - Performance analysis</div>
                                <div><code>codeforge:optimize:cache</code> - Optimize caches</div>
                                <div><code>codeforge:backup:metrics</code> - Backup metrics</div>
                                <div><code>codeforge:health:trends</code> - Analyze trends</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">External Monitoring Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>// Integrate with external monitoring
    class CodeForgeMonitoringService
    {
        public function sendMetricsToDatadog()
        {
            $healthService = app(DatabaseHealthService::class);
            $metrics = $healthService->getPerformanceMetrics();

            // Send to Datadog
            \Datadog::gauge('codeforge.response_time', $metrics['response_time']);
            \Datadog::increment('codeforge.health_checks');
        }

        public function sendAlertsToSlack()
        {
            $health = app(DatabaseHealthService::class)->getConnectionStatus();

            if (!$health['connected']) {
                \Slack::to('#alerts')->send('CodeForge: Database connection failed');
            }
        }

        public function integrateWithNewRelic()
        {
            if (extension_loaded('newrelic')) {
                newrelic_custom_metric('CodeForge/HealthScore', $this->getHealthScore());
                newrelic_custom_metric('CodeForge/QueryCount', $this->getQueryCount());
            }
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deployment Automation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Deployment Automation</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">CI/CD Pipeline Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># .github/workflows/deploy.yml
    name: Deploy CodeForge

    on:
      push:
        branches: [main]

    jobs:
      deploy:
        runs-on: ubuntu-latest

        steps:
        - uses: actions/checkout@v3

        - name: Setup PHP
          uses: shivammathur/setup-php@v2
          with:
            php-version: '8.2'
            extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite

        - name: Install dependencies
          run: composer install --no-dev --optimize-autoloader

        - name: Run CodeForge tests
          run: php artisan test --testsuite=CodeForge

        - name: Deploy to production
          run: |
            php artisan config:cache
            php artisan route:cache  
            php artisan view:cache
            php artisan codeforge:health:check

        - name: Notify deployment
          run: |
            php artisan codeforge:deployment:notify \
              --environment=production \
              --version=$GITHUB_SHA</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Zero-Downtime Deployment</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># deployment script
    #!/bin/bash

    # Deployment script for CodeForge
    echo "Starting CodeForge deployment..."

    # 1. Backup current state
    php artisan codeforge:backup:create --include-config --include-data

    # 2. Put application in maintenance mode (optional)
    # php artisan down --retry=60

    # 3. Update code
    git pull origin main

    # 4. Install/update dependencies
    composer install --no-dev --optimize-autoloader

    # 5. Update CodeForge
    php artisan vendor:publish --tag=codeforge-config --force
    php artisan migrate --force

    # 6. Clear and rebuild caches
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # 7. Verify CodeForge health
    php artisan codeforge:health:check --fail-on-error

    # 8. Bring application back online
    # php artisan up

    # 9. Verify deployment
    php artisan codeforge:deployment:verify

    echo "CodeForge deployment completed successfully!"</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Troubleshooting -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Production Troubleshooting</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Issues & Solutions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <h4 class="font-semibold text-gray-900 mb-2">Configuration Issues</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>Missing config:</strong> Run <code>php artisan vendor:publish --tag=codeforge-database-studio-config</code></li>
                                <li>• <strong>Stale cache:</strong> Run <code>php artisan config:clear</code></li>
                                <li>• <strong>Migration errors:</strong> Check <code>php artisan migrate:status</code></li>
                                <li>• <strong>Permission errors:</strong> Verify Filament admin access policies</li>
                            </ul>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <h4 class="font-semibold text-gray-900 mb-2">Performance Issues</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>Slow Monitoring:</strong> Increase check intervals</li>
                                <li>• <strong>High Memory:</strong> Reduce metrics retention</li>
                                <li>• <strong>Database Load:</strong> Optimize query thresholds</li>
                                <li>• <strong>Cache Problems:</strong> Configure Redis properly</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Diagnostic Commands</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Comprehensive system check
    php artisan codeforge:diagnose --verbose

    # Test database connections
    php artisan codeforge:test:connections --all

    # Verify configuration
    php artisan codeforge:config:verify

    # Check permissions
    php artisan codeforge:permissions:check

    # Performance analysis
    php artisan codeforge:analyze:performance --last-24h

    # Export logs for support
    php artisan codeforge:logs:export --format=zip</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deployment Best Practices -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Deployment Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for successful CodeForge production deployment:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Security Guidelines</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Restrict Features:</strong> Disable dangerous operations in production</li>
                        <li>• <strong>Access Control:</strong> Implement role-based access control</li>
                        <li>• <strong>Audit Logging:</strong> Enable comprehensive audit trails</li>
                        <li>• <strong>Network Security:</strong> Use IP whitelisting if required</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Operational Guidelines</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Monitoring Setup:</strong> Configure health monitoring and alerting</li>
                        <li>• <strong>Backup Strategy:</strong> Regular backups of configurations and data</li>
                        <li>• <strong>Performance Tuning:</strong> Optimize for your specific workload</li>
                        <li>• <strong>Documentation:</strong> Document custom configurations</li>
                        <li>• <strong>Team Training:</strong> Train team on production procedures</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-white rounded-lg border border-indigo-200">
                <h4 class="font-semibold text-gray-900 mb-2">🚀 Production Checklist</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>☐ Production security settings enabled</li>
                    <li>☐ Dangerous features disabled</li>
                    <li>☐ Monitoring and alerting configured</li>
                    <li>☐ Backup and recovery procedures tested</li>
                    <li>☐ Team access and permissions configured</li>
                    <li>☐ Performance optimizations applied</li>
                    <li>☐ Deployment automation tested</li>
                </ul>
            </div>
        </div>
    </div>
@endsection