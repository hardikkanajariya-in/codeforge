{{-- Error Message Component for CodeForge Database Studio --}}
<div class="fi-section-content-text text-sm">
    <div
        class="fi-color-danger rounded-lg border border-danger-600 bg-danger-50 p-4 dark:border-danger-400 dark:bg-danger-950">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-filament::icon icon="heroicon-o-exclamation-triangle"
                    class="fi-icon-size-md text-danger-500 dark:text-danger-400" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-danger-800 dark:text-danger-200">
                    Error Occurred
                </h3>
                <div class="mt-2 text-sm text-danger-700 dark:text-danger-300">
                    <p>{{ $error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
