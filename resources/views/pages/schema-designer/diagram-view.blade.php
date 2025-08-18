<div wire:ignore>
    @if($visualizationData)
        <div id="schema-diagram" class="schema-diagram" style="height: 600px; width: 100%;"></div>
        <div class="hidden" id="diagram-data">{{ json_encode($visualizationData) }}</div>
    @else
        <div class="flex items-center justify-center text-gray-500" style="height: 600px; width: 100%;">
            <div class="text-center">
                <x-heroicon-o-exclamation-circle class="w-8 h-8 mx-auto mb-2" />
                <p>No visualization data available.</p>
                <p class="text-sm">Click "Refresh Schema" to load data.</p>
            </div>
        </div>
    @endif
</div>
