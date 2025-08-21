@extends('codeforge-studio::layout.docs')

@section('title', 'Security Architecture - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>Architecture</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Security</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Security Architecture</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio implements enterprise-grade security features
                including commercial license validation, data protection, and comprehensive access controls to ensure safe
                database operations.</p>
        </div>

        <!-- Security Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Security Framework</h2>
            <p class="text-gray-600 mb-6">Multi-layered security approach combining commercial license validation, data
                encryption, access controls, and audit logging for comprehensive protection.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">License Validation</h3>
                    </div>
                    <p class="text-sm text-gray-600">Commercial license verification with Anystack API integration,
                        fingerprinting, and anti-tampering protection.</p>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Data Protection</h3>
                    </div>
                    <p class="text-sm text-gray-600">Encryption at rest and in transit, secure credential storage, and
                        protection against data breaches and unauthorized access.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Audit Logging</h3>
                    </div>
                    <p class="text-sm text-gray-600">Comprehensive audit trails for all operations, security events, and
                        administrative actions with immutable logging.</p>
                </div>
            </div>
        </div>

        <!-- License Validation System -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Commercial License Validation</h2>

            <div class="space-y-6">
                <!-- License Service Architecture -->
                <div class="border-l-4 border-red-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">LicenseValidationService</h3>
                    <p class="text-gray-600 mb-3">Enterprise-grade license validation with Anystack API integration:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class LicenseValidationService
    {
        protected string $baseUrl = 'https://api.anystack.sh';
        protected int $cacheMinutes = 60;  // 1 hour cache TTL

        public function validateLicense(string $licenseKey): LicenseValidationResult
        {
            // Check cache first for performance
            $cacheKey = "license_validation_{$licenseKey}";

            return Cache::remember($cacheKey, $this->cacheMinutes, function () use ($licenseKey) {
                return $this->validateWithAnystack($licenseKey);
            });
        }

        protected function validateWithAnystack(string $licenseKey): LicenseValidationResult
        {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => $this->getUserAgent()])
                ->post("{$this->baseUrl}/v1/licenses/validate", [
                    'license_key' => $licenseKey,
                    'product_id' => config('codeforge-studio.product_id'),
                    'fingerprint' => $this->generateFingerprint(),
                ]);

            return LicenseValidationResult::fromResponse($response);
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Fingerprinting System -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Device Fingerprinting</h3>
                    <p class="text-gray-600 mb-3">Unique device identification for license enforcement:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>protected function generateFingerprint(): string
    {
        $components = [
            PHP_OS,                          // Operating system
            php_uname('n'),                  // Hostname
            $_SERVER['SERVER_NAME'] ?? '',   // Server name
            $_SERVER['HTTP_HOST'] ?? '',     // HTTP host
            base_path(),                     // Application path
        ];

        // Filter out empty components
        $components = array_filter($components);

        // Generate secure hash
        return hash('sha256', implode('|', $components));
    }</code></pre>
                    </div>
                </div>

                <!-- License Types -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">License Tiers</h3>
                    <p class="text-gray-600 mb-3">Commercial licensing with two-tier structure:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded border">
                                <h4 class="font-semibold text-gray-900 mb-2">Regular License - $99</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Single commercial project</li>
                                    <li>• Full feature access</li>
                                    <li>• Email support</li>
                                    <li>• 6 months updates</li>
                                </ul>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <h4 class="font-semibold text-gray-900 mb-2">Extended License - $349</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Unlimited commercial projects</li>
                                    <li>• Priority support</li>
                                    <li>• Custom development</li>
                                    <li>• Lifetime updates</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Protection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Protection & Encryption</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Encryption at Rest -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Encryption at Rest</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• <strong>Database Encryption:</strong> AES-256 encryption for sensitive data columns</li>
                        <li>• <strong>File Encryption:</strong> Laravel's built-in encryption for stored files</li>
                        <li>• <strong>Configuration Security:</strong> Environment variables for secrets</li>
                        <li>• <strong>Key Management:</strong> Secure key rotation and storage</li>
                    </ul>
                    <div class="mt-4 bg-white p-3 rounded border">
                        <pre class="text-xs text-gray-700 overflow-x-auto"><code>// Example encrypted model attribute
    protected $casts = [
        'license_key' => 'encrypted',
        'api_credentials' => 'encrypted:array',
    ];</code></pre>
                    </div>
                </div>

                <!-- Encryption in Transit -->
                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Encryption in Transit</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• <strong>HTTPS Enforcement:</strong> TLS 1.2+ for all communications</li>
                        <li>• <strong>API Security:</strong> Secure API endpoints with authentication</li>
                        <li>• <strong>Database Connections:</strong> SSL/TLS encrypted database connections</li>
                        <li>• <strong>Certificate Validation:</strong> Proper SSL certificate verification</li>
                    </ul>
                    <div class="mt-4 bg-white p-3 rounded border">
                        <pre class="text-xs text-gray-700 overflow-x-auto"><code>// Database SSL configuration
    'mysql' => [
        'sslmode' => 'require',
        'options' => [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
        ],
    ],</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access Control -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Access Control & Authentication</h2>

            <div class="space-y-6">
                <!-- Filament Integration -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Filament Authentication</h3>
                    <p class="text-gray-600 mb-3">Leverages Filament's robust authentication and authorization system:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// Plugin authorization check
    public function authorize(array $parameters = []): bool
    {
        // Check if user has admin access
        if (!auth()->check()) {
            return false;
        }

        // Verify license status
        $licenseService = app(LicenseValidationService::class);
        $validation = $licenseService->validateLicense(
            config('codeforge-studio.license_key')
        );

        return $validation->isValid() && $validation->isActive();
    }</code></pre>
                    </div>
                </div>

                <!-- Permission-based Access -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Permission-based Security</h3>
                    <p class="text-gray-600 mb-3">Granular permissions for different plugin features:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong class="text-gray-900">Database Operations:</strong>
                                <ul class="text-gray-600 mt-1 space-y-1">
                                    <li>• view_database_health</li>
                                    <li>• manage_migrations</li>
                                    <li>• execute_queries</li>
                                    <li>• modify_schema</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="text-gray-900">Code Generation:</strong>
                                <ul class="text-gray-600 mt-1 space-y-1">
                                    <li>• generate_models</li>
                                    <li>• create_migrations</li>
                                    <li>• build_resources</li>
                                    <li>• export_documentation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Security Monitoring & Audit</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Security Logging -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h4 class="font-semibold text-gray-900 mb-3">Security Event Logging</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• License validation attempts</li>
                        <li>• Failed authentication events</li>
                        <li>• Permission violations</li>
                        <li>• Suspicious activity detection</li>
                    </ul>
                </div>

                <!-- Audit Trails -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h4 class="font-semibold text-gray-900 mb-3">Comprehensive Audit Trails</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• All database operations</li>
                        <li>• Schema modifications</li>
                        <li>• Code generation activities</li>
                        <li>• Configuration changes</li>
                    </ul>
                </div>

                <!-- Threat Detection -->
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <h4 class="font-semibold text-gray-900 mb-3">Threat Detection</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• Unusual access patterns</li>
                        <li>• License tampering attempts</li>
                        <li>• Brute force protection</li>
                        <li>• Rate limiting enforcement</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Security Configuration -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Security Configuration</h2>
            <p class="text-gray-600 mb-6">Essential security settings and configuration options:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Environment Configuration</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code># .env security settings
    CODEFORGE_LICENSE_KEY=your_license_key_here
    CODEFORGE_PRODUCT_ID=codeforge-database-studio
    CODEFORGE_API_TIMEOUT=10
    CODEFORGE_CACHE_TTL=60

    # Security headers
    SESSION_SECURE_COOKIE=true
    SESSION_HTTP_ONLY=true
    SESSION_SAME_SITE=strict</code></pre>
                </div>

                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Plugin Configuration</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// config/codeforge-studio.php
    return [
        'license_key' => env('CODEFORGE_LICENSE_KEY'),
        'product_id' => env('CODEFORGE_PRODUCT_ID'),
        'security' => [
            'require_license' => true,
            'cache_validation' => true,
            'log_violations' => true,
            'rate_limit' => 100, // per hour
        ],
    ];</code></pre>
                </div>
            </div>
        </div>

        <!-- Security Best Practices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Security Best Practices</h2>
            <p class="text-gray-600 mb-6">Recommended security practices when using CodeForge Database Studio:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Deployment Security</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Secure License Storage:</strong> Store license keys in environment variables</li>
                        <li>• <strong>HTTPS Only:</strong> Never use the plugin over insecure connections</li>
                        <li>• <strong>Database Backups:</strong> Regular encrypted backups of sensitive data</li>
                        <li>• <strong>Access Restrictions:</strong> Limit admin panel access to trusted IPs</li>
                        <li>• <strong>Version Updates:</strong> Keep plugin and dependencies updated</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Operational Security</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>User Management:</strong> Regular audit of user permissions</li>
                        <li>• <strong>Log Monitoring:</strong> Monitor security logs for anomalies</li>
                        <li>• <strong>Backup Verification:</strong> Test backup restoration procedures</li>
                        <li>• <strong>Incident Response:</strong> Have security incident response plan</li>
                        <li>• <strong>Security Training:</strong> Train team on security best practices</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-yellow-800 mb-1">Security Notice</h4>
                        <p class="text-yellow-700 text-sm">This plugin contains powerful database management capabilities.
                            Always follow security best practices and restrict access to authorized personnel only. Regular
                            security audits are recommended for production environments.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection