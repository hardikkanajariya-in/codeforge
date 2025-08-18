<x-filament-panels::page>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/codeforge/css/schema-designer-v2.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endpush

    <!-- Advanced Schema Designer Container -->
    <div class="schema-designer-v2" x-data="schemaDesigner()" x-init="initialize()">

        <!-- Header Stats Panel -->
        <div class="stats-panel">
            @php $stats = $this->schemaData['statistics'] ?? [] @endphp

            <div class="stat-card-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <x-heroicon-o-table-cells class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_tables'] ?? 0 }}</div>
                        <div class="stat-label">Tables</div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <x-heroicon-o-link class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_relationships'] ?? 0 }}</div>
                        <div class="stat-label">Relationships</div>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-icon">
                        <x-heroicon-o-squares-2x2 class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_columns'] ?? 0 }}</div>
                        <div class="stat-label">Columns</div>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <x-heroicon-o-circle-stack class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_rows'] ?? 0) }}</div>
                        <div class="stat-label">Records</div>
                    </div>
                </div>

                <div class="stat-card secondary">
                    <div class="stat-icon">
                        <x-heroicon-o-chart-bar class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['relationship_density'] ?? 0 }}</div>
                        <div class="stat-label">Density</div>
                    </div>
                </div>

                <div class="stat-card accent">
                    <div class="stat-icon">
                        <x-heroicon-o-cpu-chip class="w-6 h-6" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ round($stats['average_columns_per_table'] ?? 0, 1) }}</div>
                        <div class="stat-label">Avg Columns</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="control-panel">
            <div class="control-section">
                <h3 class="control-title">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                    Schema Designer
                </h3>
                <p class="control-subtitle">Interactive database visualization and design</p>
            </div>

            <div class="control-actions">
                <!-- View Mode Selector -->
                <div class="view-mode-selector">
                    <button class="view-btn" :class="{ 'active': activeView === 'interactive' }"
                        @click="switchView('interactive')" title="Interactive Designer">
                        <x-heroicon-o-cursor-arrow-rays class="w-4 h-4" />
                        <span>Designer</span>
                    </button>

                    <button class="view-btn" :class="{ 'active': activeView === 'table_detail' }"
                        @click="switchView('table_detail')" title="Table Details">
                        <x-heroicon-o-table-cells class="w-4 h-4" />
                        <span>Tables</span>
                    </button>

                    <button class="view-btn" :class="{ 'active': activeView === 'dependencies' }"
                        @click="switchView('dependencies')" title="Dependencies">
                        <x-heroicon-o-arrow-path-rounded-square class="w-4 h-4" />
                        <span>Dependencies</span>
                    </button>

                    <button class="view-btn" :class="{ 'active': activeView === 'performance' }"
                        @click="switchView('performance')" title="Performance">
                        <x-heroicon-o-bolt class="w-4 h-4" />
                        <span>Performance</span>
                    </button>

                    <button class="view-btn" :class="{ 'active': activeView === 'matrix' }"
                        @click="switchView('matrix')" title="Relationship Matrix">
                        <x-heroicon-o-squares-plus class="w-4 h-4" />
                        <span>Matrix</span>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button class="action-btn primary" @click="refreshSchema" title="Refresh Schema">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                    </button>

                    <button class="action-btn success" @click="applyAutoLayout" title="Auto Layout">
                        <x-heroicon-o-sparkles class="w-4 h-4" />
                    </button>

                    <button class="action-btn warning" @click="exportSchema" title="Export Schema">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    </button>

                    <button class="action-btn secondary" @click="openSettings" title="Settings">
                        <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-workspace" :class="{ 'sidebar-open': showSidebar }">

            <!-- Sidebar -->
            <div class="workspace-sidebar" x-show="showSidebar" x-transition:enter="slide-in-left"
                x-transition:leave="slide-out-left">

                <!-- Search & Filters -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">Search & Filter</h4>

                    <div class="search-box">
                        <x-heroicon-o-magnifying-glass class="search-icon w-4 h-4" />
                        <input type="text" class="search-input" placeholder="Search tables, columns..."
                            x-model="searchQuery" @input.debounce.300ms="performSearch" />
                        <button class="search-clear" @click="clearSearch" x-show="searchQuery">
                            <x-heroicon-o-x-mark class="w-3 h-3" />
                        </button>
                    </div>

                    <div class="filter-options">
                        <label class="filter-checkbox">
                            <input type="checkbox" x-model="showRelationships" @change="toggleRelationships">
                            <span class="checkmark"></span>
                            Show Relationships
                        </label>

                        <label class="filter-checkbox">
                            <input type="checkbox" x-model="showIndexes" @change="toggleIndexes">
                            <span class="checkmark"></span>
                            Show Indexes
                        </label>

                        <label class="filter-checkbox">
                            <input type="checkbox" x-model="filterSettings.show_system_tables" @change="updateFilters">
                            <span class="checkmark"></span>
                            System Tables
                        </label>

                        <label class="filter-checkbox">
                            <input type="checkbox" x-model="filterSettings.show_empty_tables" @change="updateFilters">
                            <span class="checkmark"></span>
                            Empty Tables
                        </label>
                    </div>
                </div>

                <!-- Connection Selector -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">Database Connection</h4>
                    <select class="connection-select" x-model="selectedConnection" @change="switchConnection">
                        @foreach($this->availableConnections as $connection)
                            <option value="{{ $connection['name'] }}">
                                {{ $connection['name'] }} ({{ $connection['driver'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Table List -->
                <div class="sidebar-section flex-1">
                    <h4 class="sidebar-title">Tables</h4>
                    <div class="table-list">
                        <template x-for="table in filteredTables" :key="table.name">
                            <div class="table-item" :class="{ 'selected': selectedTable === table.name }"
                                @click="selectTable(table.name)" @dblclick="focusTable(table.name)">
                                <div class="table-icon">
                                    <x-heroicon-o-table-cells class="w-4 h-4" />
                                </div>
                                <div class="table-info">
                                    <div class="table-name" x-text="table.name"></div>
                                    <div class="table-meta">
                                        <span x-text="table.columns.length + ' cols'"></span>
                                        <span x-text="formatNumber(table.row_count) + ' rows'"></span>
                                    </div>
                                </div>
                                <div class="table-actions">
                                    <button @click.stop="bookmarkTable(table.name)" title="Bookmark">
                                        <x-heroicon-o-bookmark class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- View Options -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">View Options</h4>

                    <div class="view-controls">
                        <div class="zoom-controls">
                            <button @click="zoomOut" class="zoom-btn">
                                <x-heroicon-o-minus class="w-3 h-3" />
                            </button>
                            <span class="zoom-level" x-text="zoomLevel + '%'"></span>
                            <button @click="zoomIn" class="zoom-btn">
                                <x-heroicon-o-plus class="w-3 h-3" />
                            </button>
                        </div>

                        <button @click="fitToScreen" class="fit-btn">
                            <x-heroicon-o-arrows-pointing-in class="w-4 h-4" />
                            Fit to Screen
                        </button>

                        <button @click="resetView" class="reset-btn">
                            <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
                            Reset View
                        </button>
                    </div>
                </div>
            </div>

            <!-- Canvas Area -->
            <div class="workspace-canvas">

                <!-- Canvas Toolbar -->
                <div class="canvas-toolbar">
                    <div class="toolbar-section">
                        <button @click="toggleSidebar" class="toolbar-btn" title="Toggle Sidebar">
                            <x-heroicon-o-bars-3 class="w-4 h-4" />
                        </button>

                        <div class="toolbar-divider"></div>

                        <button @click="toggleGrid" class="toolbar-btn" :class="{ 'active': showGrid }"
                            title="Toggle Grid">
                            <x-heroicon-o-squares-2x2 class="w-4 h-4" />
                        </button>

                        <button @click="toggleSnapToGrid" class="toolbar-btn" :class="{ 'active': snapToGrid }"
                            title="Snap to Grid">
                            <x-heroicon-o-stop class="w-4 h-4" />
                        </button>
                    </div>

                    <div class="toolbar-section">
                        <div class="minimap-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" x-model="showMinimap" @change="toggleMinimap">
                                <span class="toggle-slider"></span>
                            </label>
                            <span>Minimap</span>
                        </div>
                    </div>
                </div>

                <!-- Main Canvas -->
                <div class="schema-canvas" id="schema-canvas">

                    <!-- Loading State -->
                    <div x-show="isLoading" class="loading-overlay">
                        <div class="loading-spinner">
                            <div class="spinner-ring"></div>
                            <div class="loading-text">Loading schema...</div>
                        </div>
                    </div>

                    <!-- Canvas Content -->
                    <div x-show="!isLoading" class="canvas-content" id="canvas-content">
                        <!-- Dynamic content will be rendered here -->
                    </div>

                    <!-- Minimap -->
                    <div x-show="showMinimap" class="minimap" id="minimap">
                        <!-- Minimap content -->
                    </div>
                </div>
            </div>

            <!-- Detail Panel -->
            <div class="detail-panel" x-show="selectedTable" x-transition:enter="slide-in-up"
                x-transition:leave="slide-out-down">
                <div class="detail-header">
                    <h3 class="detail-title">
                        <x-heroicon-o-table-cells class="w-5 h-5" />
                        <span x-text="selectedTable"></span>
                    </h3>
                    <button @click="selectedTable = null" class="detail-close">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </button>
                </div>

                <div class="detail-content">
                    <div class="detail-tabs">
                        <button class="tab-btn active" @click="activeDetailTab = 'structure'">Structure</button>
                        <button class="tab-btn" @click="activeDetailTab = 'relationships'">Relationships</button>
                        <button class="tab-btn" @click="activeDetailTab = 'indexes'">Indexes</button>
                        <button class="tab-btn" @click="activeDetailTab = 'data'">Data</button>
                    </div>

                    <div class="detail-body">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Data for JavaScript -->
        <script type="application/json" id="schema-data">
            @json([
                'schemaData' => $this->getSchemaData(),
                'config' => $this->getVisualizationConfig(),
                'connections' => $this->availableConnections
            ])
        </script>

        @push('scripts')
            <!-- Modern Libraries -->
            <script src="https://d3js.org/d3.v7.min.js"></script>
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

            <!-- Custom Schema Designer -->
            <script src="{{ asset('vendor/codeforge/js/schema-designer-v2.js') }}"></script>
        @endpush
</x-filament-panels::page>