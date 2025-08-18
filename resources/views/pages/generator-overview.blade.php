<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Generator Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->getGeneratorStats() as $generator)
                <div
                    class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-lg bg-{{ $generator['color'] }}-100">
                                    @svg($generator['icon'], 'w-6 h-6 text-' . $generator['color'] . '-600')
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $generator['name'] }}</h3>
                            </div>
                        </div>

                        <p class="text-gray-600 text-sm mb-4">{{ $generator['description'] }}</p>

                        <div class="space-y-2 mb-4">
                            <h4 class="text-sm font-medium text-gray-900">Features:</h4>
                            <ul class="text-xs text-gray-600 space-y-1">
                                @foreach($generator['features'] as $feature)
                                    <li class="flex items-center space-x-2">
                                        <div class="w-1 h-1 bg-{{ $generator['color'] }}-400 rounded-full"></div>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ $generator['url'] }}"
                            class="inline-flex items-center justify-center w-full px-4 py-2 bg-{{ $generator['color'] }}-600 hover:bg-{{ $generator['color'] }}-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            Open Generator
                            @svg('heroicon-o-arrow-right', 'w-4 h-4 ml-2')
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Start Guide</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 mb-2">1</div>
                    <h4 class="font-medium text-gray-900 mb-1">Migration</h4>
                    <p class="text-sm text-gray-600">Create database tables and schema</p>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 mb-2">2</div>
                    <h4 class="font-medium text-gray-900 mb-1">Model</h4>
                    <p class="text-sm text-gray-600">Generate Eloquent models with relationships</p>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 mb-2">3</div>
                    <h4 class="font-medium text-gray-900 mb-1">Factory & Seeder</h4>
                    <p class="text-sm text-gray-600">Create test data and populate database</p>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600 mb-2">4</div>
                    <h4 class="font-medium text-gray-900 mb-1">Filament Resource</h4>
                    <p class="text-sm text-gray-600">Build admin interfaces and CRUD operations</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
