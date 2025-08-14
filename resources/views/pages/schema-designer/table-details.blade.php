@php
    $tableDetails = $this->getTableDetails($selectedTable);
@endphp

@if($tableDetails)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div>
            <h4 class="font-semibold text-gray-900 mb-3">Basic Information</h4>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Table Name:</dt>
                    <dd class="text-sm text-gray-900 font-mono">{{ $tableDetails['name'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Row Count:</dt>
                    <dd class="text-sm text-gray-900">{{ number_format($tableDetails['row_count']) }}</dd>
                </div>
                @if($tableDetails['size'])
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Size:</dt>
                        <dd class="text-sm text-gray-900">{{ $tableDetails['size'] }} MB</dd>
                    </div>
                @endif
                @if($tableDetails['created_at'])
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Created:</dt>
                        <dd class="text-sm text-gray-900">{{ $tableDetails['created_at'] }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <!-- Statistics -->
        <div>
            <h4 class="font-semibold text-gray-900 mb-3">Statistics</h4>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Total Columns:</dt>
                    <dd class="text-sm text-gray-900">{{ count($tableDetails['columns']) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Indexes:</dt>
                    <dd class="text-sm text-gray-900">{{ count($tableDetails['indexes']) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Foreign Keys:</dt>
                    <dd class="text-sm text-gray-900">{{ count($tableDetails['foreign_keys']) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Referenced By:</dt>
                    <dd class="text-sm text-gray-900">{{ count($tableDetails['referenced_by']) }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Columns -->
    <div class="mt-6">
        <h4 class="font-semibold text-gray-900 mb-3">Columns</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nullable
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Constraints</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tableDetails['columns'] as $column)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($column['primary_key'])
                                        <x-heroicon-s-key class="w-4 h-4 text-red-500 mr-1" title="Primary Key" />
                                    @endif
                                    <span class="text-sm font-mono font-medium text-gray-900">{{ $column['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $column['type'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                @if($column['nullable'])
                                    <span class="text-green-600">Yes</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ $column['default'] ?: '-' }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @if($column['auto_increment'])
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            AUTO_INCREMENT
                                        </span>
                                    @endif
                                    @if($column['unique'])
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            UNIQUE
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Foreign Keys -->
    @if(count($tableDetails['foreign_keys']) > 0)
        <div class="mt-6">
            <h4 class="font-semibold text-gray-900 mb-3">Foreign Keys</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Column
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                References</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Update
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Delete
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tableDetails['foreign_keys'] as $fk)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-mono font-medium text-gray-900">
                                    {{ $fk['column'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-mono text-gray-900">
                                    {{ $fk['foreign_table'] }}.{{ $fk['foreign_column'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $fk['on_update'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $fk['on_delete'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Referenced By -->
    @if(count($tableDetails['referenced_by']) > 0)
        <div class="mt-6">
            <h4 class="font-semibold text-gray-900 mb-3">Referenced By</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($tableDetails['referenced_by'] as $reference)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-medium text-blue-900">{{ $reference['table'] }}</span>
                            <x-heroicon-o-chevron-left class="w-4 h-4 text-blue-600" />
                        </div>
                        <div class="text-sm text-blue-700 mt-1">
                            {{ $reference['column'] }} → {{ $reference['references_column'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Indexes -->
    @if(count($tableDetails['indexes']) > 0)
        <div class="mt-6">
            <h4 class="font-semibold text-gray-900 mb-3">Indexes</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Column
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tableDetails['indexes'] as $index)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-mono font-medium text-gray-900">
                                    {{ $index['name'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-mono text-gray-900">
                                    {{ $index['column'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index['type'] ?? 'btree' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                    @if($index['unique'])
                                        <span class="text-green-600">Yes</span>
                                    @else
                                        <span class="text-gray-400">No</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@else
    <div class="text-center py-8 text-gray-500">
        <x-heroicon-o-exclamation-circle class="w-12 h-12 mx-auto mb-2" />
        <p>Unable to load table details.</p>
        <p class="text-sm">The table may not exist or there may be a connection issue.</p>
    </div>
@endif