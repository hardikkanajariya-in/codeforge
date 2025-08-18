<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Page Header with Actions --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Database Health Monitor</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Monitor database connections, performance metrics, and query statistics
                </p>
            </div>
        </div>

        {{-- Health Summary Cards --}}
        @if(isset($healthSummary))
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                {{-- Connection Status --}}
                @if(isset($healthSummary['connection_status']))
                    <x-filament::section>
                        <x-slot name="heading">Connection Status</x-slot>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-4 h-4 {{ $healthSummary['connection_status']['status'] === 'connected' ? 'bg-green-500' : 'bg-red-500' }} rounded-full">
                                </div>
                                <span class="text-sm font-medium">
                                    {{ ucfirst($healthSummary['connection_status']['status']) }}
                                </span>
                            </div>
                            @if(isset($healthSummary['connection_status']['response_time']))
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    Response: {{ $healthSummary['connection_status']['response_time'] }}ms
                                </div>
                            @endif
                        </div>
                    </x-filament::section>
                @endif

                {{-- Performance Summary --}}
                @if(isset($healthSummary['performance_summary']))
                    <x-filament::section>
                        <x-slot name="heading">Performance</x-slot>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Queries Today:</span>
                                <span
                                    class="font-medium">{{ number_format($healthSummary['performance_summary']['queries_today']) }}</span>
                            </div>
                            @if($healthSummary['performance_summary']['avg_response_time'])
                                <div class="flex justify-between text-sm">
                                    <span>Avg Response:</span>
                                    <span
                                        class="font-medium">{{ number_format($healthSummary['performance_summary']['avg_response_time'], 2) }}ms</span>
                                </div>
                            @endif
                            @if($healthSummary['performance_summary']['error_rate'] > 0)
                                <div class="flex justify-between text-sm text-red-600 dark:text-red-400">
                                    <span>Errors:</span>
                                    <span class="font-medium">{{ $healthSummary['performance_summary']['error_rate'] }}</span>
                                </div>
                            @endif
                        </div>
                    </x-filament::section>
                @endif

                {{-- Active Alerts --}}
                @if(isset($healthSummary['alerts']) && count($healthSummary['alerts']) > 0)
                    <x-filament::section>
                        <x-slot name="heading">Active Alerts</x-slot>
                        <div class="space-y-2">
                            @foreach(array_slice($healthSummary['alerts'], 0, 3) as $alert)
                                <div
                                    class="p-2 {{ $alert['status'] === 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-red-50 dark:bg-red-900/20' }} rounded">
                                    <div
                                        class="text-xs {{ $alert['status'] === 'warning' ? 'text-yellow-800 dark:text-yellow-200' : 'text-red-800 dark:text-red-200' }}">
                                        {{ $alert['metric_name'] }}: {{ $alert['formatted_value'] ?? $alert['value'] }}
                                    </div>
                                </div>
                            @endforeach
                            @if(count($healthSummary['alerts']) > 3)
                                <div class="text-xs text-gray-500">
                                    +{{ count($healthSummary['alerts']) - 3 }} more alerts
                                </div>
                            @endif
                        </div>
                    </x-filament::section>
                @else
                    <x-filament::section>
                        <x-slot name="heading">System Status</x-slot>
                        <div class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm">All systems operational</span>
                        </div>
                    </x-filament::section>
                @endif

                {{-- Quick Actions --}}
                <x-filament::section>
                    <x-slot name="heading">Quick Actions</x-slot>
                    <div class="space-y-2">
                        <x-filament::button size="sm" color="info" wire:click="$refresh" class="w-full">
                            Refresh Data
                        </x-filament::button>
                        @php
                            $queryLogsExists = false;
                            try {
                                $queryLogsExists = Route::has('filament.admin.resources.query-performance-logs.index');
                            } catch (\Exception $e) {
                                try {
                                    route('filament.admin.resources.query-performance-logs.index');
                                    $queryLogsExists = true;
                                } catch (\Exception $e2) {
                                    $queryLogsExists = false;
                                }
                            }
                        @endphp
                        @if($queryLogsExists)
                            <x-filament::button size="sm" color="warning" tag="a"
                                href="{{ route('filament.admin.resources.query-performance-logs.index') }}" class="w-full">
                                View Query Logs
                            </x-filament::button>
                        @endif
                    </div>
                </x-filament::section>
            </div>
        @endif
    </div>
</x-filament-panels::page>
