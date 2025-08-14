<div class="filament-resource-preview">
    <div class="space-y-4">
        <div class="text-sm text-gray-600">
            <strong>Resource Name:</strong> {{ $record->resource_name }}
        </div>

        @if($record->model_name)
            <div class="text-sm text-gray-600">
                <strong>Model:</strong> {{ $record->model_name }}
            </div>
        @endif

        @if($record->table_name)
            <div class="text-sm text-gray-600">
                <strong>Table:</strong> {{ $record->table_name }}
            </div>
        @endif

        <div class="border-t pt-4">
            @if(isset($error))
                <div class="text-center py-8">
                    <div class="text-red-500">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-red-900">Error generating preview</h3>
                        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
                    </div>
                </div>
            @elseif(isset($preview) && is_array($preview) && count($preview) > 0)
                @include('codeforge-database-studio::components.preview-generated-code', ['codeFiles' => $preview])
            @else
                <div class="text-center py-8">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Unable to generate preview</h3>
                        <p class="mt-1 text-sm text-gray-500">Please check the configuration and try again.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>