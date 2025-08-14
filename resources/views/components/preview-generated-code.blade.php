<div class="filament-resource-generator-preview">
    @if(isset($codeFiles) ? count($codeFiles) > 0 : count($getCodeFiles()) > 0)
        @php
            $files = $codeFiles ?? $getCodeFiles();
            // Ensure files are properly formatted
            $files = collect($files)->filter(function ($file) {
                return is_array($file) && isset($file['name']) && isset($file['content']);
            })->toArray();
        @endphp

        @if(count($files) > 0)
            <div x-data="{ activeTab: 0 }" class="space-y-4">
                <div class="text-sm text-gray-600 font-medium mb-4">
                    Generated Resource Files Preview
                </div>

                <!-- Filament-style tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        @foreach($files as $index => $file)
                            <button type="button" @click="activeTab = {{ $index }}" :class="{
                                                'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === {{ $index }},
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== {{ $index }}
                                            }"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                                {{ $file['name'] }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                <!-- Tab content -->
                @foreach($files as $index => $file)
                    <div x-show="activeTab === {{ $index }}" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="code-preview-content">
                        <div class="bg-gray-900 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                            <!-- File header -->
                            <div
                                class="bg-gray-800 px-4 py-2 text-xs text-gray-300 font-mono border-b border-gray-700 flex items-center justify-between">
                                <span>{{ $file['name'] }}</span>
                                <span class="text-gray-500">Read-only preview</span>
                            </div>

                            <!-- Code content -->
                            <div class="p-0 overflow-x-auto max-h-96 overflow-y-auto">
                                <pre
                                    class="text-sm font-mono leading-relaxed p-4 m-0"><code>{{ $file['content'] }}</code></pre>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Invalid file format</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The code files are not in the expected format.</p>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500">
            <x-heroicon-o-document class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No code generated yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure your resource settings and generate code to
                see the preview.</p>
        </div>
    @endif
</div>