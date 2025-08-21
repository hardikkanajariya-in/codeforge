@extends('codeforge-studio::layout.docs')

@section('title', 'API Services - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>API</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Services</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">API Services</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio provides 17+ service classes implementing core
                functionality through dependency injection and singleton registration for optimal performance.</p>
        </div>

        <!-- Services Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Architecture</h2>
            <p class="text-gray-600 mb-6">All services are registered as singletons in the Laravel container, providing
                efficient memory usage and consistent state across the application lifecycle.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Database Services</h3>
                    </div>
                    <p class="text-sm text-gray-600">Health monitoring, schema analysis, and migration tracking services.
                    </p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Code Generation</h3>
                    </div>
                    <p class="text-sm text-gray-600">Model, migration, seeder, and Filament resource generation services.
                    </p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Data & Documentation</h3>
                    </div>
                    <p class="text-sm text-gray-600">Data generation, seeding execution, and documentation services.</p>
                </div>
            </div>
        </div>

        <!-- Core Database Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Database Services</h2>

            <div class="space-y-6">
                <!-- Database Health Service -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DatabaseHealthService</h3>
                    <p class="text-gray-600 mb-3">Monitors database performance, connection status, and overall health
                        metrics.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class DatabaseHealthService
    {
        // Get overall health score for all connections
        public function getOverallHealth(): array

        // Get health metrics for specific connection
        public function getConnectionHealth(string $connection = null): array

        // Check connection status
        public function checkConnection(string $connection = null): bool

        // Get performance metrics
        public function getPerformanceMetrics(string $connection = null): array

        // Collect and store health data
        public function collectHealthMetrics(): void
    }</code></pre>
                    </div>
                </div>

                <!-- License Validation Service -->
                <div class="border-l-4 border-red-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">LicenseValidationService</h3>
                    <p class="text-gray-600 mb-3">Handles commercial license validation with Anystack API integration and
                        caching.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class LicenseValidationService
    {
        // Validate license key with caching
        public function validateLicense(string $licenseKey): LicenseValidationResult

        // Generate device fingerprint for license binding
        protected function generateFingerprint(): string

        // Check if license is currently valid and active
        public function isLicenseValid(): bool

        // Get license details and restrictions
        public function getLicenseInfo(): array

        // Clear license validation cache
        public function clearCache(): void
    }</code></pre>
                    </div>
                </div>

                <!-- Migration Tracking Service -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">MigrationTrackingService</h3>
                    <p class="text-gray-600 mb-3">Tracks migration execution, history, and provides rollback capabilities.
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class MigrationTrackingService
    {
        // Get all migration files and their status
        public function getAllMigrations(): Collection

        // Get executed migrations with execution details
        public function getExecutedMigrations(): Collection

        // Get pending migrations
        public function getPendingMigrations(): Collection

        // Track migration execution
        public function trackMigration(string $migration, string $action): void

        // Get migration history for specific file
        public function getMigrationHistory(string $migration): Collection
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Generation Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Code Generation Services</h2>

            <div class="space-y-6">
                <!-- Model Generator Service -->
                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">ModelGeneratorService</h3>
                    <p class="text-gray-600 mb-3">Generates Eloquent models with relationships, casts, and proper
                        documentation.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class ModelGeneratorService
    {
        // Generate model for specific table
        public function generateModel(string $tableName, array $options = []): string

        // Generate model with relationships
        public function generateModelWithRelationships(string $tableName): string

        // Get model template with customizations
        public function getModelTemplate(string $tableName): string

        // Generate model attributes and casts
        protected function generateAttributes(string $tableName): array

        // Save generated model to file
        public function saveModel(string $content, string $modelName): bool
    }</code></pre>
                    </div>
                </div>

                <!-- Filament Resource Generator Service -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">FilamentResourceGeneratorService</h3>
                    <p class="text-gray-600 mb-3">Creates complete Filament admin resources with forms, tables, and actions.
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class FilamentResourceGeneratorService
    {
        // Generate complete Filament resource
        public function generateResource(string $modelName, array $options = []): array

        // Generate form schema for resource
        public function generateFormSchema(string $tableName): array

        // Generate table columns configuration
        public function generateTableColumns(string $tableName): array

        // Generate resource actions and bulk actions
        public function generateActions(string $modelName): array

        // Save resource files (Resource, CreatePage, EditPage, ListPage)
        public function saveResourceFiles(array $files, string $resourceName): bool
    }</code></pre>
                    </div>
                </div>

                <!-- Seeder Generator Service -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">SeederGeneratorService</h3>
                    <p class="text-gray-600 mb-3">Creates intelligent database seeders with relationship-aware data
                        generation.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class SeederGeneratorService
    {
        // Generate seeder for table with intelligent data
        public function generateSeeder(string $tableName, int $count = 10): string

        // Generate seeder with custom template
        public function generateCustomSeeder(string $tableName, array $template): string

        // Create factory-based seeder
        public function generateFactorySeeder(string $modelName, int $count): string

        // Generate relationship-aware seeder
        public function generateRelationalSeeder(string $tableName): string

        // Save seeder to file
        public function saveSeeder(string $content, string $seederName): bool
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Management Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Management Services</h2>

            <div class="space-y-6">
                <!-- Data Generation Service -->
                <div class="border-l-4 border-teal-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DataGenerationService</h3>
                    <p class="text-gray-600 mb-3">Generates realistic test data with relationship handling and custom
                        templates.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class DataGenerationService
    {
        // Generate data for table with count
        public function generateData(string $tableName, int $count = 10): array

        // Generate data using custom template
        public function generateWithTemplate(string $tableName, array $template): array

        // Generate relationship-aware data
        public function generateRelationalData(string $tableName, int $count): array

        // Bulk insert generated data
        public function insertGeneratedData(string $tableName, array $data): bool

        // Get available data templates
        public function getAvailableTemplates(string $tableName): array
    }</code></pre>
                    </div>
                </div>

                <!-- Seeder Execution Service -->
                <div class="border-l-4 border-cyan-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">SeederExecutionService</h3>
                    <p class="text-gray-600 mb-3">Manages seeder execution with logging, error handling, and progress
                        tracking.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class SeederExecutionService
    {
        // Run specific seeder with logging
        public function runSeeder(string $seederClass): SeederResult

        // Run multiple seeders in sequence
        public function runSeeders(array $seederClasses): array

        // Run all seeders with dependency resolution
        public function runAllSeeders(): array

        // Get seeder execution logs
        public function getExecutionLogs(string $seederClass = null): Collection

        // Check if seeder has been executed
        public function hasSeederRun(string $seederClass): bool
    }</code></pre>
                    </div>
                </div>

                <!-- Documentation Generation Service -->
                <div class="border-l-4 border-pink-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DocumentationGenerationService</h3>
                    <p class="text-gray-600 mb-3">Generates comprehensive documentation for database schema and API
                        endpoints.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class DocumentationGenerationService
    {
        // Generate database schema documentation
        public function generateSchemaDocumentation(): string

        // Generate API documentation
        public function generateApiDocumentation(): string

        // Generate documentation in specific format (markdown, html, pdf)
        public function generateDocumentation(string $format = 'markdown'): string

        // Export documentation to file
        public function exportDocumentation(string $content, string $format): string

        // Get documentation templates
        public function getAvailableTemplates(): array
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Usage Examples -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Usage Examples</h2>
            <p class="text-gray-600 mb-6">Practical examples of using CodeForge Database Studio services in your
                application:</p>

            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Dependency Injection</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// In controller constructor
    public function __construct(
        private DatabaseHealthService $healthService,
        private CodeGenerationService $codeService,
        private LicenseValidationService $licenseService
    ) {}

    // In service method
    public function generateModels()
    {
        // Check license first
        if (!$this->licenseService->isLicenseValid()) {
            throw new UnauthorizedAccessException();
        }

        // Generate models for all tables
        $tables = Schema::getTableListing();
        foreach ($tables as $table) {
            $this->codeService->generateModel($table);
        }
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Service Resolution</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Manual resolution from container
    $healthService = app(DatabaseHealthService::class);
    $health = $healthService->getOverallHealth();

    // Using helper function
    $dataService = resolve(DataGenerationService::class);
    $data = $dataService->generateData('users', 100);

    // Facade-style access (if implemented)
    $metrics = DatabaseHealth::getPerformanceMetrics();</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Service Configuration</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// In service provider boot method
    $this->app->when(DatabaseHealthService::class)
        ->needs('$connections')
        ->give(function () {
            return config('database.connections');
        });

    // Service with custom configuration
    $generator = app(ModelGeneratorService::class);
    $generator->setTemplate('custom_model_template');
    $generator->setNamespace('App\\CustomModels');
    $model = $generator->generateModel('users');</code></pre>
                </div>
            </div>
        </div>

        <!-- Service Registration -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Registration</h2>
            <p class="text-gray-600 mb-6">All services are automatically registered in the Laravel container as singletons
                for optimal performance:</p>

            <div class="bg-white p-6 rounded-lg border">
                <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// In CodeForgeStudioServiceProvider
    public function register(): void
    {
        // Core database services
        $this->app->singleton(DatabaseHealthService::class);
        $this->app->singleton(LicenseValidationService::class);
        $this->app->singleton(MigrationTrackingService::class);

        // Code generation services
        $this->app->singleton(ModelGeneratorService::class);
        $this->app->singleton(FilamentResourceGeneratorService::class);
        $this->app->singleton(SeederGeneratorService::class);

        // Data management services
        $this->app->singleton(DataGenerationService::class);
        $this->app->singleton(SeederExecutionService::class);
        $this->app->singleton(DocumentationGenerationService::class);

        // Additional specialized services
        $this->app->singleton(IntelligentSuggestionService::class);
        $this->app->singleton(StubTemplateService::class);
        $this->app->singleton(AssetService::class);
    }</code></pre>
            </div>
        </div>
    </div>
@endsection