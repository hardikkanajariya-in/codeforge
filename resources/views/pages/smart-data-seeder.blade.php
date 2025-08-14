<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="background-color: white; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border-radius: 0.5rem; padding: 1.5rem;" class="dark:bg-gray-800">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h2 style="font-size: 1.125rem; font-weight: 500; color: #111827;" class="dark:text-gray-100">
                    Smart Data Generation
                </h2>
                <div style="font-size: 0.875rem; color: #6b7280;" class="dark:text-gray-400">
                    Generate realistic test data for your database tables
                </div>
            </div>

            <div style="max-width: none; margin-bottom: 1.5rem;" class="prose dark:prose-invert">
                <p style="font-size: 0.875rem; color: #4b5563;" class="dark:text-gray-300">
                    This tool helps you generate realistic test data for your database tables. You can either:
                </p>
                <ul style="font-size: 0.875rem; color: #4b5563; list-style-type: disc; margin-left: 1rem;" class="dark:text-gray-300">
                    <li>Auto-generate data based on table structure analysis</li>
                    <li>Use existing templates for consistent data generation</li>
                    <li>Create custom generation rules for specific business needs</li>
                </ul>
            </div>
        </div>

        {{ $this->form }}

        @if(!empty($this->tableAnalysis))
            <div style="background-color: white; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border-radius: 0.5rem; padding: 1.5rem;" class="dark:bg-gray-800">
                <h3 style="font-size: 1.125rem; font-weight: 500; color: #111827; margin-bottom: 1rem;" class="dark:text-gray-100">
                    Table Analysis: {{ $this->selectedTable }}
                </h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="md:grid-cols-2">
                    <div>
                        <h4 style="font-weight: 500; color: #111827; margin-bottom: 0.5rem;" class="dark:text-gray-100">Columns</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($this->tableAnalysis['columns'] ?? [] as $column)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background-color: #f9fafb; border-radius: 0.375rem;" class="dark:bg-gray-700">
                                    <span style="font-family: ui-monospace, SFMono-Regular, 'SF Mono', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 0.875rem;">{{ $column['name'] }}</span>
                                    <span style="font-size: 0.75rem; color: #6b7280; background-color: #e5e7eb; padding: 0.25rem 0.5rem; border-radius: 0.375rem;" class="dark:bg-gray-600">
                                        {{ $column['type'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4 style="font-weight: 500; color: #111827; margin-bottom: 0.5rem;" class="dark:text-gray-100">Generation Suggestions</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($this->tableAnalysis['suggestions'] ?? [] as $column => $suggestion)
                                <div style="padding: 0.5rem; background-color: #eff6ff; border-radius: 0.375rem;" class="dark:bg-blue-900/20">
                                    <div style="font-family: ui-monospace, SFMono-Regular, 'SF Mono', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 0.875rem;">{{ $column }}</div>
                                    <div style="font-size: 0.75rem; color: #2563eb;" class="dark:text-blue-400">
                                        {{ $suggestion['type'] ?? 'string' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!empty($this->tableAnalysis['relationships']))
                    <div style="margin-top: 1.5rem;">
                        <h4 style="font-weight: 500; color: #111827; margin-bottom: 0.5rem;" class="dark:text-gray-100">Detected Relationships</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($this->tableAnalysis['relationships'] ?? [] as $relationship)
                                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background-color: #f0fdf4; border-radius: 0.375rem;" class="dark:bg-green-900/20">
                                    <span style="font-family: ui-monospace, SFMono-Regular, 'SF Mono', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 0.875rem;">{{ $relationship['column'] }}</span>
                                    <span style="font-size: 0.75rem; color: #16a34a;" class="dark:text-green-400">→</span>
                                    <span style="font-size: 0.875rem; color: #16a34a;" class="dark:text-green-400">{{ $relationship['related_table'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>