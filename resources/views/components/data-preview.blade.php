{{-- Data Preview Component for CodeForge Database Studio --}}
<div class="fi-section-content-text">
    <div class="overflow-hidden shadow ring-1 ring-gray-950/5 rounded-xl dark:ring-white/10">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    @if(!empty($data))
                        @foreach(array_keys($data[0]) as $column)
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ $column }}
                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($data as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        @foreach($row as $value)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                @if(is_array($value) || is_object($value))
                                    <code
                                        class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2 py-1 rounded font-mono">{{ json_encode($value) }}</code>
                                @elseif(is_bool($value))
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $value ? 'bg-success-50 text-success-700 dark:bg-success-400/10 dark:text-success-400' : 'bg-danger-50 text-danger-700 dark:bg-danger-400/10 dark:text-danger-400' }}">
                                        {{ $value ? 'true' : 'false' }}
                                    </span>
                                @elseif(is_null($value))
                                    <span class="text-gray-400 dark:text-gray-500 italic">null</span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <x-filament::icon icon="heroicon-o-information-circle" class="fi-icon-size-md text-info-500" />
        <span>Showing preview of {{ count($data) }} records for table: <code
                class="font-mono bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded text-xs">{{ $template->table_name }}</code></span>
    </div>
</div>
