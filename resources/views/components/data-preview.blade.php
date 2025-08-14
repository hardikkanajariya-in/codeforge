<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                @if(!empty($data))
                    @foreach(array_keys($data[0]) as $column)
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ $column }}
                        </th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($data as $row)
                <tr>
                    @foreach($row as $value)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            @if(is_array($value) || is_object($value))
                                <code
                                    class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ json_encode($value) }}</code>
                            @elseif(is_bool($value))
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $value ? 'true' : 'false' }}
                                </span>
                            @elseif(is_null($value))
                                <span class="text-gray-400 italic">null</span>
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

<div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
    Showing preview of {{ count($data) }} records for table: <code class="font-mono">{{ $tableName }}</code>
</div>