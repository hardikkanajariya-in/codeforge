<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header with snapshot information -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Comparing: {{ $this->record->name }}
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Captured on {{ $this->record->captured_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Database</div>
                    <div class="font-medium">{{ $this->record->database_connection }}</div>
                </div>
            </div>
        </div>

        <!-- Comparison form -->
        {{ $this->form }}

        <!-- Comparison results -->
        @if($this->comparison)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Comparison Results</h3>

                <!-- Summary cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-green-900">
                                    {{ count($this->comparison['changes']['added_tables'] ?? []) }}
                                </div>
                                <div class="text-green-700">Added Tables</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-red-900">
                                    {{ count($this->comparison['changes']['removed_tables'] ?? []) }}
                                </div>
                                <div class="text-red-700">Removed Tables</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-yellow-900">
                                    {{ count($this->comparison['changes']['modified_tables'] ?? []) }}
                                </div>
                                <div class="text-yellow-700">Modified Tables</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed changes -->
                <div class="space-y-6">
                    @if(!empty($this->comparison['changes']['added_tables']))
                        <div>
                            <h4 class="text-md font-semibold text-green-800 mb-3">Added Tables</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach($this->comparison['changes']['added_tables'] as $table)
                                    <div class="bg-green-100 text-green-800 px-3 py-2 rounded-md text-sm font-medium">
                                        {{ $table }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($this->comparison['changes']['removed_tables']))
                        <div>
                            <h4 class="text-md font-semibold text-red-800 mb-3">Removed Tables</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach($this->comparison['changes']['removed_tables'] as $table)
                                    <div class="bg-red-100 text-red-800 px-3 py-2 rounded-md text-sm font-medium">
                                        {{ $table }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($this->comparison['changes']['modified_tables']))
                        <div>
                            <h4 class="text-md font-semibold text-yellow-800 mb-3">Modified Tables</h4>
                            <div class="space-y-4">
                                @foreach($this->comparison['changes']['modified_tables'] as $tableChange)
                                    <div class="border border-yellow-200 rounded-lg p-4">
                                        <h5 class="font-medium text-yellow-900 mb-2">{{ $tableChange['table'] }}</h5>

                                        @if(!empty($tableChange['changes']['added_columns']))
                                            <div class="mb-2">
                                                <span class="text-sm font-medium text-green-700">Added Columns:</span>
                                                <span class="text-sm text-green-600 ml-2">
                                                    {{ implode(', ', $tableChange['changes']['added_columns']) }}
                                                </span>
                                            </div>
                                        @endif

                                        @if(!empty($tableChange['changes']['removed_columns']))
                                            <div class="mb-2">
                                                <span class="text-sm font-medium text-red-700">Removed Columns:</span>
                                                <span class="text-sm text-red-600 ml-2">
                                                    {{ implode(', ', $tableChange['changes']['removed_columns']) }}
                                                </span>
                                            </div>
                                        @endif

                                        @if(!empty($tableChange['changes']['modified_columns']))
                                            <div class="mb-2">
                                                <span class="text-sm font-medium text-yellow-700">Modified Columns:</span>
                                                <span class="text-sm text-yellow-600 ml-2">
                                                    {{ implode(', ', array_keys($tableChange['changes']['modified_columns'])) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if(
                        count($this->comparison['changes']['added_tables'] ?? []) === 0 &&
                        count($this->comparison['changes']['removed_tables'] ?? []) === 0 &&
                        count($this->comparison['changes']['modified_tables'] ?? []) === 0
                    )
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No Changes Detected</h3>
                        <p class="mt-1 text-gray-500">The selected snapshots have identical database schemas.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>