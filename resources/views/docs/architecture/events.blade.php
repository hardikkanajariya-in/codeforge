@extends('codeforge-studio::layout.docs')

@section('title', 'Events Architecture - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Events</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Events Architecture</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio's event-driven architecture enables loose coupling,
                real-time updates, and extensible workflows throughout the plugin system.</p>
        </div>

        <!-- Event System Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Event System Overview</h2>
            <p class="text-gray-600 mb-6">The plugin leverages Laravel's robust event system to provide decoupled
                communication between services, real-time notifications, and extensible hooks for custom functionality.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Service Events</h3>
                    </div>
                    <p class="text-sm text-gray-600">Database operations, code generation, and service interactions trigger
                        events for monitoring and extending functionality.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0-15 0v5h5l-5 5-5-5h5z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Real-time Updates</h3>
                    </div>
                    <p class="text-sm text-gray-600">Filament's reactive interface updates automatically when database
                        changes or operations complete via event broadcasting.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Custom Hooks</h3>
                    </div>
                    <p class="text-sm text-gray-600">Developers can listen to plugin events to integrate custom logic,
                        logging, or external system notifications.</p>
                </div>
            </div>
        </div>

        <!-- Event Categories -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Event Categories</h2>

            <div class="space-y-6">
                <!-- Database Events -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Operation Events</h3>
                    <p class="text-gray-600 mb-3">Events triggered during database operations and health monitoring:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>• <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded">DatabaseHealthChecked</code> -
                                Health metrics calculated</li>
                            <li>• <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded">SchemaAnalyzed</code> - Schema
                                analysis completed</li>
                            <li>• <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded">MigrationExecuted</code> -
                                Migration run or rolled back</li>
                            <li>• <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded">QueryPerformanceLogged</code> -
                                Slow query detected</li>
                        </ul>
                    </div>
                </div>

                <!-- Code Generation Events -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Code Generation Events</h3>
                    <p class="text-gray-600 mb-3">Events fired during automated code generation processes:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>• <code class="text-green-600 bg-green-50 px-2 py-1 rounded">ModelGenerated</code> -
                                Eloquent model created</li>
                            <li>• <code class="text-green-600 bg-green-50 px-2 py-1 rounded">MigrationGenerated</code> -
                                Migration file created</li>
                            <li>• <code
                                    class="text-green-600 bg-green-50 px-2 py-1 rounded">FilamentResourceGenerated</code> -
                                Admin resource scaffolded</li>
                            <li>• <code class="text-green-600 bg-green-50 px-2 py-1 rounded">SeederGenerated</code> -
                                Database seeder created</li>
                        </ul>
                    </div>
                </div>

                <!-- Data Events -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Data Management Events</h3>
                    <p class="text-gray-600 mb-3">Events related to data generation and seeding operations:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>• <code class="text-purple-600 bg-purple-50 px-2 py-1 rounded">DataGenerated</code> - Test
                                data generation completed</li>
                            <li>• <code class="text-purple-600 bg-purple-50 px-2 py-1 rounded">SeederExecuted</code> -
                                Seeder run successfully</li>
                            <li>• <code class="text-purple-600 bg-purple-50 px-2 py-1 rounded">DataTemplateCreated</code> -
                                Custom template saved</li>
                            <li>• <code class="text-purple-600 bg-purple-50 px-2 py-1 rounded">BulkDataProcessed</code> -
                                Large dataset operation completed</li>
                        </ul>
                    </div>
                </div>

                <!-- Documentation Events -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Documentation Events</h3>
                    <p class="text-gray-600 mb-3">Events triggered during documentation generation:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>• <code class="text-orange-600 bg-orange-50 px-2 py-1 rounded">DocumentationGenerated</code>
                                - Documentation export completed</li>
                            <li>• <code class="text-orange-600 bg-orange-50 px-2 py-1 rounded">SchemaDocumented</code> -
                                Database schema documented</li>
                            <li>• <code class="text-orange-600 bg-orange-50 px-2 py-1 rounded">APIDocumented</code> - API
                                endpoints documented</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Usage Examples -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Usage Examples</h2>
            <p class="text-gray-600 mb-6">Practical examples of listening to and handling CodeForge Database Studio events:
            </p>

            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Creating Event Listeners</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// app/Listeners/DatabaseHealthListener.php
    use HkDevs\CodeForgeStudio\Events\DatabaseHealthChecked;

    class DatabaseHealthListener
    {
        public function handle(DatabaseHealthChecked $event)
        {
            // Send notification if health score is low
            if ($event->healthScore < 70) {
                Notification::route('slack', config('monitoring.slack_webhook'))
                    ->notify(new DatabaseHealthAlert($event->metrics));
            }

            // Log health metrics
            Log::info('Database health checked', [
                'score' => $event->healthScore,
                'connection' => $event->connection,
                'metrics' => $event->metrics
            ]);
        }
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Registering Event Listeners</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// app/Providers/EventServiceProvider.php
    protected $listen = [
        \HkDevs\CodeForgeStudio\Events\DatabaseHealthChecked::class => [
            \App\Listeners\DatabaseHealthListener::class,
        ],
        \HkDevs\CodeForgeStudio\Events\ModelGenerated::class => [
            \App\Listeners\ModelGeneratedListener::class,
        ],
        \HkDevs\CodeForgeStudio\Events\MigrationExecuted::class => [
            \App\Listeners\MigrationAuditListener::class,
        ],
    ];</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Custom Event Handling</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Custom listener for code generation events
    class CodeGenerationAuditListener
    {
        public function handle($event)
        {
            // Create audit trail for generated code
            DB::table('code_generation_audit')->insert([
                'event_type' => class_basename($event),
                'generated_file' => $event->filePath,
                'template_used' => $event->template,
                'user_id' => auth()->id(),
                'created_at' => now(),
            ]);

            // Trigger custom webhook
            Http::post(config('audit.webhook_url'), [
                'type' => 'code_generated',
                'file' => $event->filePath,
                'timestamp' => now()->toISOString(),
            ]);
        }
    }</code></pre>
                </div>
            </div>
        </div>

        <!-- Event Broadcasting -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Real-time Event Broadcasting</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio integrates with Laravel's broadcasting system for
                real-time UI updates:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Frontend Integration</h4>
                    <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto"><code>// Listen for real-time updates
    Echo.channel('codeforge.database')
        .listen('DatabaseHealthChecked', (e) => {
            updateHealthWidget(e.metrics);
        })
        .listen('MigrationExecuted', (e) => {
            refreshMigrationStatus();
        });</code></pre>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Filament Integration</h4>
                    <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto"><code>// Auto-refresh Filament components
    public function getPollingInterval(): ?string
    {
        return '5s'; // Poll every 5 seconds
    }

    public function refreshComponent(): void
    {
        // React to broadcast events
        $this->dispatch('refresh');
    }</code></pre>
                </div>
            </div>
        </div>

        <!-- Event Best Practices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for working with CodeForge Database Studio events:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Performance Considerations</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Queue Heavy Operations:</strong> Use Laravel queues for intensive event handling</li>
                        <li>• <strong>Avoid Blocking:</strong> Keep event listeners lightweight and fast</li>
                        <li>• <strong>Batch Processing:</strong> Group similar events for efficient processing</li>
                        <li>• <strong>Selective Listening:</strong> Only listen to events you actually need</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Error Handling</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Graceful Degradation:</strong> Handle event listener failures gracefully</li>
                        <li>• <strong>Dead Letter Queues:</strong> Configure failed job handling</li>
                        <li>• <strong>Monitoring:</strong> Log event processing for debugging</li>
                        <li>• <strong>Fallback Logic:</strong> Implement fallback mechanisms for critical events</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection