<div class="space-y-4">
    @if(!empty($errors))
        <div class="bg-red-50 rounded-lg border border-red-200 p-4">
            <ul class="space-y-2">
                @foreach($errors as $error)
                    <li class="flex items-start space-x-2">
                        <x-heroicon-o-exclamation-circle class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" />
                        <span class="text-sm text-red-700">{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-gray-500 text-sm">No validation errors found.</p>
        </div>
    @endif
</div>
