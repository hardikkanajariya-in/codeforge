<x-filament-panels::page>
    <div>
        @if($currentStep === 'configuration')
            <div class="space-y-6">
                <!-- Configuration Form -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Seeder Configuration</h3>
                    {{ $this->form }}
                </div>
            </div>
        @elseif($currentStep === 'preview')
            <div class="space-y-6">
                <!-- Preview -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Code Preview</h3>

                    @if($previewData)
                        @foreach($previewData as $fileType => $data)
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">{{ ucfirst($fileType) }}</h4>
                                    <span class="text-sm text-gray-500">{{ $data['file_path'] }}</span>
                                </div>
                                <pre
                                    class="bg-gray-900 text-dark p-4 rounded-lg overflow-x-auto text-sm"><code>{{ $data['content'] }}</code></pre>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @elseif($currentStep === 'results')
            <div class="space-y-6">
                <!-- Results -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Generation Results</h3>

                    @if($generationResults && $generationResults['success'])
                        <div class="space-y-4">
                            <div class="flex items-center space-x-2 text-green-600">
                                @svg('heroicon-o-check-circle', 'w-5 h-5')
                                <span class="font-medium">Seeder generated successfully!</span>
                            </div>

                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-900">Files Created:</h4>
                                @foreach($generationResults['files_created'] as $file)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $file['class_name'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $file['path'] }}</div>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ ucfirst($file['type']) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        @if($isGenerating)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
                        <span class="text-gray-900 font-medium">Generating seeder...</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>