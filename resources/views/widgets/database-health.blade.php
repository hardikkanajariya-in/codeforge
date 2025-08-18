<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Database Health Monitor
        </x-slot>

        <div class="space-y-6">
            {{-- Connection Status Grid --}}
            @if(isset($connectionStatus) && is_array($connectionStatus))
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($connectionStatus as $connection => $status)
                        @if(is_array($status) && isset($status['status']))
                            <div
                                class="p-4 border rounded-lg {{ $status['status'] === 'connected' ? 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20' : 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-3 h-3 {{ $status['status'] === 'connected' ? 'bg-green-500' : 'bg-red-500' }} rounded-full">
                                        </div>
                                        <span class="text-sm font-medium">{{ $connection }}</span>
                                    </div>
                                    @if(isset($status['response_time']) && $status['response_time'])
                                        <span
                                            class="text-xs px-2 py-1 rounded {{ $status['response_time'] < 100 ? 'bg-green-100 text-green-800' : ($status['response_time'] < 500 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $status['response_time'] }}ms
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $status['message'] ?? 'No message available' }}</p>
                                @if(isset($status['timestamp']))
                                    <p class="text-xs text-gray-500 mt-1">{{ $status['timestamp']->diffForHumans() }}</p>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @elseif(isset($connectionStatus) && !empty($connectionStatus))
                {{-- Single Connection Status (backward compatibility) --}}
                @php
                    // Get the first connection status if connectionStatus is an array of connections
                    $singleStatus = is_array($connectionStatus) && !isset($connectionStatus['status']) 
                        ? reset($connectionStatus) 
                        : $connectionStatus;
                @endphp
                @if($singleStatus && isset($singleStatus['status']))
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @if($singleStatus['status'] === 'connected')
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Connected</span>
                            @else
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Disconnected</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $singleStatus['connection'] ?? 'default' }}</div>
                            @if(isset($singleStatus['response_time']) && $singleStatus['response_time'])
                                <div class="text-xs text-gray-500">{{ $singleStatus['response_time'] }}ms</div>
                            @endif
                        </div>
                    </div>

                    {{-- Status Message --}}
                    <div
                        class="p-3 @if($singleStatus['status'] === 'connected') bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200 @else bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 @endif rounded-lg">
                        <p class="text-sm">{{ $singleStatus['message'] ?? 'No status message available' }}</p>
                    </div>
                @endif
            @endif

            {{-- Performance Metrics --}}
            @if(isset($performanceMetrics) && !empty($performanceMetrics))
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Performance Metrics</h4>

                    @if(isset($performanceMetrics['query_performance']))
                        <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4 mb-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="text-xs text-blue-600 dark:text-blue-400">Total Queries (24h)</div>
                                <div class="text-lg font-semibold text-blue-900 dark:text-blue-100">
                                    {{ number_format(isset($performanceMetrics['query_performance']['total_queries']) ? $performanceMetrics['query_performance']['total_queries'] : 0) }}
                                </div>
                            </div>

                            @if(isset($performanceMetrics['query_performance']['avg_execution_time']) && $performanceMetrics['query_performance']['avg_execution_time'])
                                @php $avgTime = $performanceMetrics['query_performance']['avg_execution_time']; @endphp
                                <div
                                    class="p-3 {{ $avgTime < 100 ? 'bg-green-50 dark:bg-green-900/20' : ($avgTime < 500 ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-red-50 dark:bg-red-900/20') }} rounded-lg">
                                    <div
                                        class="text-xs {{ $avgTime < 100 ? 'text-green-600 dark:text-green-400' : ($avgTime < 500 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        Avg Response Time</div>
                                    <div
                                        class="text-lg font-semibold {{ $avgTime < 100 ? 'text-green-900 dark:text-green-100' : ($avgTime < 500 ? 'text-yellow-900 dark:text-yellow-100' : 'text-red-900 dark:text-red-100') }}">
                                        {{ number_format($avgTime, 2) }}ms
                                    </div>
                                </div>
                            @endif

                                                        @php $slowQueries = isset($performanceMetrics['query_performance']['slow_queries']) ? $performanceMetrics['query_performance']['slow_queries'] : 0; @endphp
                            <div
                                class="p-3 {{ $slowQueries > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20' }} rounded-lg">
                                <div
                                    class="text-xs {{ $slowQueries > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Slow Queries
                                </div>
                                <div
                                    class="text-lg font-semibold {{ $slowQueries > 0 ? 'text-red-900 dark:text-red-100' : 'text-green-900 dark:text-green-100' }}">
                                    {{ number_format($slowQueries) }}
                                </div>
                            </div>

                                                        @php $failedQueries = isset($performanceMetrics['query_performance']['failed_queries']) ? $performanceMetrics['query_performance']['failed_queries'] : 0; @endphp
                            <div
                                class="p-3 {{ $failedQueries > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20' }} rounded-lg">
                                <div
                                    class="text-xs {{ $failedQueries > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Failed Queries
                                </div>
                                <div
                                    class="text-lg font-semibold {{ $failedQueries > 0 ? 'text-red-900 dark:text-red-100' : 'text-green-900 dark:text-green-100' }}">
                                    {{ number_format($failedQueries) }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($performanceMetrics['database_metrics']) && !empty($performanceMetrics['database_metrics']))
                        <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                            @if(isset($performanceMetrics['database_metrics']['database_size']))
                                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                    <div class="text-xs text-purple-600 dark:text-purple-400">Database Size</div>
                                    <div class="text-lg font-semibold text-purple-900 dark:text-purple-100">
                                        {{ $performanceMetrics['database_metrics']['database_size'] }} MB
                                    </div>
                                </div>
                            @endif

                            @if(isset($performanceMetrics['database_metrics']['active_connections']))
                                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                                    <div class="text-xs text-indigo-600 dark:text-indigo-400">Active Connections</div>
                                    <div class="text-lg font-semibold text-indigo-900 dark:text-indigo-100">
                                        {{ number_format($performanceMetrics['database_metrics']['active_connections']) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- Health Summary Alerts --}}
            @if(isset($healthSummary['alerts']) && !empty($healthSummary['alerts']))
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Active Alerts</h4>
                    <div class="space-y-2">
                        @foreach($healthSummary['alerts'] as $alert)
                            <div
                                class="p-3 {{ $alert['status'] === 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }} border rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-2 h-2 {{ $alert['status'] === 'warning' ? 'bg-yellow-500' : 'bg-red-500' }} rounded-full">
                                        </div>
                                        <span class="text-sm font-medium">{{ $alert['metric_name'] }}</span>
                                    </div>
                                    <span
                                        class="text-xs px-2 py-1 {{ $alert['status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800' }} rounded">
                                        {{ ucfirst($alert['status']) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    Value: {{ $alert['formatted_value'] ?? $alert['value'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Activity --}}
            @if(!empty($recentActivity))
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Recent Activity</h4>
                    <div class="space-y-2">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded text-xs">
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                        {{ $activity->action }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $activity->description }}</span>
                                </div>
                                <span
                                    class="text-gray-500">{{ \Carbon\Carbon::parse($activity->executed_at)->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
