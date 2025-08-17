{{--
Execution Output Component

Displays seeder execution output and error messages in a formatted,
readable manner with proper styling and syntax highlighting.

@param string $output - The execution output content
@param string|null $error - The error message content (optional)

@package HkDevs\CodeForgeStudio
@author hardikkanajariya.in
@version 1.0.0
--}}

<div class="space-y-4">
    {{-- Output Section --}}
    @if($output)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg border">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Execution Output
                </h3>
            </div>
            <div class="p-4">
                <pre
                    class="bg-gray-900 text-green-400 p-4 rounded-md overflow-x-auto text-sm font-mono whitespace-pre-wrap">{{ $output }}</pre>
            </div>
        </div>
    @else
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg border p-4 text-center">
            <p class="text-gray-500 dark:text-gray-400">No output available</p>
        </div>
    @endif

    {{-- Error Section --}}
    @if($error)
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
            <div class="px-4 py-3 border-b border-red-200 dark:border-red-800">
                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Error Message
                </h3>
            </div>
            <div class="p-4">
                <pre
                    class="bg-red-900 text-red-100 p-4 rounded-md overflow-x-auto text-sm font-mono whitespace-pre-wrap">{{ $error }}</pre>
            </div>
        </div>
    @endif

    {{-- No content message --}}
    @if(!$output && !$error)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg border p-8 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No Output Available</h3>
            <p class="text-gray-500 dark:text-gray-400">No execution output or error messages were captured for this seeder
                execution.</p>
        </div>
    @endif
</div>