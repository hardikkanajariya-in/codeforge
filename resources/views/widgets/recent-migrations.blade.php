<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Recent Migrations
        </x-slot>

        @if(!empty($recentMigrations))
            <div class="space-y-3">
                @foreach($recentMigrations as $migration)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                {{ basename($migration['migration']) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $migration['executed_at'] }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                {{ $migration['status'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No migrations found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by running your first migration.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>