<x-filament-panels::page>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/codeforge-database-studio/css/schema-designer.css') }}">
    @endpush

    <!-- Custom Schema Designer with D3.js -->
    <div class="schema-designer-container">
        <!-- Header Statistics -->
        <div class="stats-grid">
            @php $stats = $this->getStatistics() @endphp

            <div class="stat-card">
                <div class="stat-icon">
                    <x-heroicon-o-table-cells class="w-6 h-6 text-blue-500" />
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_tables'] ?? 0 }}</div>
                    <div class="stat-label">Tables</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <x-heroicon-o-squares-2x2 class="w-6 h-6 text-green-500" />
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_columns'] ?? 0 }}</div>
                    <div class="stat-label">Columns</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <x-heroicon-o-arrow-path-rounded-square class="w-6 h-6 text-purple-500" />
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_relationships'] ?? 0 }}</div>
                    <div class="stat-label">Relations</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <x-heroicon-o-chart-bar class="w-6 h-6 text-orange-500" />
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['total_rows'] ?? 0) }}</div>
                    <div class="stat-label">Records</div>
                </div>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="control-panel">
            <div class="control-title">
                <h3>Database Schema Designer</h3>
                <p>Interactive visualization of your database structure</p>
            </div>

            <div class="search-controls">
                <div class="search-input">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 text-gray-400" />
                    <input type="text" placeholder="Search tables and columns..." oninput="handleSearch(this.value)" />
                </div>

                <div class="view-controls">
                    <button onclick="toggleRelationships()" class="control-btn" title="Toggle Relationships">
                        <x-heroicon-o-arrow-path-rounded-square class="w-4 h-4" />
                        <span>Relations</span>
                    </button>
                    <button onclick="resetView()" class="control-btn" title="Reset View">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                        <span>Reset</span>
                    </button>
                    <button onclick="fitToScreen()" class="control-btn" title="Fit to Screen">
                        <x-heroicon-o-arrows-pointing-in class="w-4 h-4" />
                        <span>Fit</span>
                    </button>
                    <button wire:click="exportERD" class="control-btn" title="Export Schema">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Visualization Container -->
            <div class="visualization-container">
                <!-- Loading State -->
                <div id="loading-indicator" class="loading-state"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                    <div class="loading-spinner"
                        style="width: 40px; height: 40px; border: 4px solid #f3f4f6; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite;">
                    </div>
                    <div class="loading-text" style="color: #6b7280; font-size: 14px;">Loading schema visualization...
                    </div>
                </div>

                <!-- Schema Canvas -->
                <div id="schema-canvas" class="schema-canvas"></div>
            </div>

            <!-- Details Panel - Below the canvas -->
            <div id="details-panel" class="details-panel" style="display: none;">
                <div class="details-header">
                    <h3 id="details-title">Table Details</h3>
                    <button onclick="closeDetailsPanel()" class="close-btn">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </button>
                </div>
                <div id="details-content" class="details-content">
                    <div class="no-selection">
                        <div class="no-selection-icon">
                            <x-heroicon-o-cursor-arrow-rays class="w-8 h-8 text-gray-400" />
                        </div>
                        <p class="text-gray-500">Click on a table to view its details</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-title">Legend</div>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-color primary-key"></div>
                    <span>Primary Key</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color foreign-key"></div>
                    <span>Foreign Key</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color relationship-line"></div>
                    <span>Relationship</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color nullable-field"></div>
                    <span>Nullable Field</span>
                </div>
            </div>
        </div>

        <!-- Schema Data (JSON) -->
        <script type="application/json" id="schema-data">
        @json($this->getViewData())
        </script>

        @push('scripts')
            <script src="https://d3js.org/d3.v7.min.js"></script>
            <script src="{{ asset('vendor/codeforge-database-studio/js/schema-designer.js') }}"></script>
            <style>
                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                .loading-state {
                    z-index: 1000;
                    background: rgba(255, 255, 255, 0.9);
                }
            </style>
            <script>
                // Handle file downloads
                document.addEventListener('livewire:init', () => {
                    Livewire.on('download-file', (event) => {
                        const data = event[0] || event;
                        if (data.url) {
                            // Create temporary link and trigger download
                            const link = document.createElement('a');
                            link.href = data.url;
                            link.download = data.filename || 'schema_export.json';
                            link.style.display = 'none';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    });
                });

                // Global functions
                window.handleSearch = function (value) {
                    console.log('handleSearch called with:', value);
                    if (window.schemaDesigner && window.schemaDesigner.search) {
                        window.schemaDesigner.search(value);
                    } else {
                        console.error('schemaDesigner or search method not available');
                    }
                };

                window.toggleRelationships = function () {
                    console.log('toggleRelationships called');
                    if (window.schemaDesigner && window.schemaDesigner.toggleRelationships) {
                        window.schemaDesigner.toggleRelationships();
                    } else {
                        console.error('schemaDesigner or toggleRelationships method not available');
                    }
                };

                window.resetView = function () {
                    console.log('resetView called');
                    if (window.schemaDesigner && window.schemaDesigner.resetView) {
                        window.schemaDesigner.resetView();
                    } else {
                        console.error('schemaDesigner or resetView method not available');
                    }
                };

                window.fitToScreen = function () {
                    console.log('fitToScreen called');
                    if (window.schemaDesigner && window.schemaDesigner.fitToScreen) {
                        window.schemaDesigner.fitToScreen();
                    } else {
                        console.error('schemaDesigner or fitToScreen method not available');
                    }
                };

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function () {
                    console.log('Schema Designer page loaded');

                    // Check if SchemaDesigner instance is available after a short delay
                    setTimeout(() => {
                        if (window.schemaDesigner) {
                            console.log('SchemaDesigner instance is available:', window.schemaDesigner);
                        } else {
                            console.error('SchemaDesigner instance not available after page load');
                        }
                    }, 1000);
                });
            </script>
        @endpush
    </div>
</x-filament-panels::page>