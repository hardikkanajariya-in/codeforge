/**
 * Advanced Schema Designer V2 JavaScript
 * 
 * A comprehensive, modern schema visualization tool with Alpine.js and D3.js
 * Following best practices for performance, accessibility, and user experience.
 * 
 * Features:
 * - Interactive drag-and-drop table positioning
 * - Real-time schema analysis and visualization  
 * - Advanced filtering and search capabilities
 * - Multiple visualization modes and layouts
 * - Responsive design with mobile support
 * - Performance optimized for large schemas
 * 
 * @author hardikkanajariya.in
 * @version 2.0.0
 */

// Alpine.js Schema Designer Component
function schemaDesigner() {
    return {
        // Core State
        activeView: 'interactive',
        selectedConnection: '',
        selectedTable: null,
        selectedTables: [],
        searchQuery: '',
        isLoading: false,

        // UI State
        showSidebar: true,
        showMinimap: false,
        showGrid: false,
        snapToGrid: true,
        showRelationships: true,
        showIndexes: true,
        zoomLevel: 100,

        // Data
        schemaData: {},
        filteredTables: [],
        visualizationEngine: null,

        // Settings
        filterSettings: {
            show_system_tables: false,
            show_empty_tables: true,
            show_laravel_tables: true,
            table_name_pattern: '',
            min_columns: 0,
            max_columns: 999,
        },

        activeDetailTab: 'structure',

        // Initialization
        async initialize() {
            try {
                console.log('🚀 Initializing Advanced Schema Designer V2...');

                // Load data from JSON script tag
                this.loadInitialData();

                // Setup basic functionality
                this.setupBasicFunctionality();

                // Apply initial filters
                this.updateFilteredTables();

                // Initialize visualization (basic version)
                this.initializeBasicVisualization();

                console.log('✅ Schema Designer initialized successfully');

            } catch (error) {
                console.error('❌ Failed to initialize Schema Designer:', error);
                this.handleError('Failed to initialize schema designer', error);
            }
        },

        // Basic functionality setup
        setupBasicFunctionality() {
            // Set loading to false after initialization
            this.isLoading = false;

            // Default to showing sidebar
            this.showSidebar = true;

            // Set default active view
            this.activeView = 'interactive';

            console.log('🔧 Basic functionality initialized');
        },

        // Data Management
        loadInitialData() {
            const dataElement = document.getElementById('schema-data');
            if (!dataElement) {
                throw new Error('Schema data not found');
            }

            const data = JSON.parse(dataElement.textContent);
            this.schemaData = data.schemaData || {};
            this.selectedConnection = data.config?.ui_state?.selectedConnection || '';

            // Set initial UI state from config
            const uiState = data.config?.ui_state || {};
            this.showRelationships = uiState.show_relationships ?? true;
            this.showIndexes = uiState.show_indexes ?? true;
            this.showSidebar = uiState.show_sidebar ?? true;
            this.zoomLevel = uiState.zoom ?? 100;

            console.log('📊 Loaded schema data:', {
                tables: this.schemaData.tables?.length || 0,
                relationships: this.schemaData.relationships?.length || 0,
                connection: this.selectedConnection
            });
        },

        // Visualization Engine
        initializeVisualization() {
            this.visualizationEngine = new SchemaVisualizationEngine({
                container: '#canvas-content',
                data: this.schemaData,
                config: {
                    showRelationships: this.showRelationships,
                    showIndexes: this.showIndexes,
                    snapToGrid: this.snapToGrid,
                    showGrid: this.showGrid,
                },
                callbacks: {
                    onTableSelect: (tableName) => this.selectTable(tableName),
                    onTableMove: (tableName, position) => this.updateTablePosition(tableName, position),
                    onViewportChange: (viewport) => this.updateViewport(viewport),
                }
            });

            this.visualizationEngine.render();
        },

        // Event Handlers
        setupEventListeners() {
            // Listen for Livewire events
            document.addEventListener('livewire:init', () => {
                Livewire.on('schema-data-loaded', (data) => {
                    this.schemaData = data;
                    this.updateFilteredTables();
                    this.visualizationEngine?.updateData(data);
                });

                Livewire.on('view-changed', (event) => {
                    this.switchView(event.view);
                });

                Livewire.on('table-selection-changed', (event) => {
                    this.selectedTable = event.table;
                });
            });

            // Window resize handler
            window.addEventListener('resize', this.debounce(() => {
                this.visualizationEngine?.handleResize();
            }, 250));

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                this.handleKeyboardShortcuts(e);
            });
        },

        // Basic visualization initialization
        initializeBasicVisualization() {
            const canvasContent = document.getElementById('canvas-content');
            if (canvasContent) {
                canvasContent.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: #64748b;">
                        <div style="font-size: 1.5rem; margin-bottom: 1rem;">🎨</div>
                        <h3 style="margin-bottom: 0.5rem;">Schema Visualization Loading...</h3>
                        <p>Advanced visualization engine will be initialized here.</p>
                        <div style="margin-top: 2rem;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; max-width: 800px; margin: 0 auto;">
                                ${this.filteredTables.slice(0, 6).map(table => `
                                    <div style="padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; background: white; cursor: pointer;" 
                                         onclick="window.schemaDesignerInstance.selectTable('${table.name}')">
                                        <h4 style="margin: 0 0 0.5rem 0; color: #0f172a;">${table.name}</h4>
                                        <p style="margin: 0; font-size: 0.875rem; color: #64748b;">
                                            ${table.columns?.length || 0} columns • ${table.row_count || 0} rows
                                        </p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }

            // Store instance globally for onclick handlers
            window.schemaDesignerInstance = this;
        },

        // View Management
        switchView(view) {
            this.activeView = view;
            console.log('🎨 Switched to view:', view);

            // Update visualization based on view
            this.updateVisualizationForView(view);
        },

        updateVisualizationForView(view) {
            const canvasContent = document.getElementById('canvas-content');
            if (!canvasContent) return;

            switch (view) {
                case 'interactive':
                    this.initializeBasicVisualization();
                    break;
                case 'table_detail':
                    this.showTableDetailView();
                    break;
                case 'dependencies':
                    this.showDependenciesView();
                    break;
                case 'performance':
                    this.showPerformanceView();
                    break;
                case 'matrix':
                    this.showMatrixView();
                    break;
                default:
                    this.initializeBasicVisualization();
            }
        },

        showTableDetailView() {
            const canvasContent = document.getElementById('canvas-content');
            canvasContent.innerHTML = `
                <div style="padding: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Table Details View</h3>
                    <div style="display: grid; gap: 1rem;">
                        ${this.filteredTables.map(table => `
                            <div style="border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; background: white;">
                                <h4 style="margin: 0 0 0.5rem 0;">${table.name}</h4>
                                <div style="font-size: 0.875rem; color: #64748b;">
                                    <p>Columns: ${table.columns?.length || 0}</p>
                                    <p>Rows: ${table.row_count || 0}</p>
                                    <p>Engine: ${table.engine || 'Unknown'}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        },

        showDependenciesView() {
            const canvasContent = document.getElementById('canvas-content');
            canvasContent.innerHTML = `
                <div style="padding: 1.5rem; text-align: center;">
                    <h3>Dependencies View</h3>
                    <p style="color: #64748b;">Table dependencies visualization will be shown here.</p>
                </div>
            `;
        },

        showPerformanceView() {
            const canvasContent = document.getElementById('canvas-content');
            canvasContent.innerHTML = `
                <div style="padding: 1.5rem; text-align: center;">
                    <h3>Performance View</h3>
                    <p style="color: #64748b;">Performance metrics and analysis will be shown here.</p>
                </div>
            `;
        },

        showMatrixView() {
            const canvasContent = document.getElementById('canvas-content');
            canvasContent.innerHTML = `
                <div style="padding: 1.5rem; text-align: center;">
                    <h3>Relationship Matrix</h3>
                    <p style="color: #64748b;">Relationship matrix visualization will be shown here.</p>
                </div>
            `;
        },

        // Table Management
        selectTable(tableName) {
            this.selectedTable = tableName;
            console.log('📋 Selected table:', tableName);

            // Update detail panel
            this.updateDetailPanel(tableName);
        },

        updateDetailPanel(tableName) {
            const table = this.filteredTables.find(t => t.name === tableName);
            if (!table) return;

            // Update detail panel content (if it exists)
            const detailBody = document.querySelector('.detail-body');
            if (detailBody) {
                detailBody.innerHTML = `
                    <div style="padding: 1rem;">
                        <h4>${table.name}</h4>
                        <p>Columns: ${table.columns?.length || 0}</p>
                        <p>Rows: ${table.row_count || 0}</p>
                        <div style="margin-top: 1rem;">
                            <h5>Columns:</h5>
                            ${table.columns?.slice(0, 5).map(col => `
                                <div style="padding: 0.25rem 0; border-bottom: 1px solid #f1f5f9;">
                                    <strong>${col.name}</strong> - ${col.type}
                                </div>
                            `).join('') || '<p>No column data available</p>'}
                        </div>
                    </div>
                `;
            }
        },

        focusTable(tableName) {
            this.selectTable(tableName);
            console.log('🎯 Focused on table:', tableName);
        },

        bookmarkTable(tableName) {
            console.log('📌 Bookmarked table:', tableName);
            // Add visual feedback
            alert(`Bookmarked table: ${tableName}`);
        },

        // Search and Filtering
        performSearch() {
            this.updateFilteredTables();
            this.updateVisualizationForView(this.activeView);
            console.log('🔍 Search performed:', this.searchQuery);
        },

        clearSearch() {
            this.searchQuery = '';
            this.performSearch();
        },

        updateFilters() {
            this.updateFilteredTables();
            this.updateVisualizationForView(this.activeView);
            console.log('🔧 Filters updated');
        },

        toggleRelationships() {
            this.showRelationships = !this.showRelationships;
            console.log('🔗 Relationships toggled:', this.showRelationships);
        },

        toggleIndexes() {
            this.showIndexes = !this.showIndexes;
            console.log('📊 Indexes toggled:', this.showIndexes);
        },

        // UI Controls
        toggleSidebar() {
            this.showSidebar = !this.showSidebar;
            console.log('📱 Sidebar toggled:', this.showSidebar);
        },

        toggleGrid() {
            this.showGrid = !this.showGrid;
            console.log('⚏ Grid toggled:', this.showGrid);
        },

        toggleSnapToGrid() {
            this.snapToGrid = !this.snapToGrid;
            console.log('🧲 Snap to grid toggled:', this.snapToGrid);
        },

        toggleMinimap() {
            this.showMinimap = !this.showMinimap;
            console.log('🗺️ Minimap toggled:', this.showMinimap);
        },

        // Zoom Controls
        zoomIn() {
            this.zoomLevel = Math.min(this.zoomLevel + 10, 200);
            console.log('🔍+ Zoomed in:', this.zoomLevel);
        },

        zoomOut() {
            this.zoomLevel = Math.max(this.zoomLevel - 10, 50);
            console.log('🔍- Zoomed out:', this.zoomLevel);
        },

        fitToScreen() {
            this.zoomLevel = 100;
            console.log('📏 Fit to screen');
        },

        resetView() {
            this.zoomLevel = 100;
            this.selectedTable = null;
            console.log('🔄 View reset');
        },

        // Action Methods
        refreshSchema() {
            this.isLoading = true;
            console.log('🔄 Refreshing schema...');

            // Simulate loading
            setTimeout(() => {
                this.isLoading = false;
                this.updateFilteredTables();
                this.updateVisualizationForView(this.activeView);
                console.log('✅ Schema refreshed');
            }, 1000);
        },

        applyAutoLayout() {
            console.log('✨ Auto layout applied');
            alert('Auto layout feature applied!');
        },

        exportSchema() {
            console.log('📤 Schema export initiated');
            alert('Schema export feature - will download schema data');
        },

        openSettings() {
            console.log('⚙️ Settings opened');
            alert('Settings panel - will open configuration options');
        },

        // Connection Management
        switchConnection() {
            console.log('🔄 Switching connection to:', this.selectedConnection);
            this.refreshSchema();
        },

        // Utility Methods
        formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        },

        handleError(message, error) {
            console.error(message, error);
            // Could integrate with Filament notifications here
        },

        updateFilteredTables() {
            const tables = this.schemaData.tables || [];

            this.filteredTables = tables.filter(table => {
                // Search filter
                if (this.searchQuery) {
                    const query = this.searchQuery.toLowerCase();
                    const matchesName = table.name.toLowerCase().includes(query);
                    const matchesColumn = table.columns?.some(col =>
                        col.name.toLowerCase().includes(query)
                    );
                    if (!matchesName && !matchesColumn) return false;
                }

                // System tables filter
                if (!this.filterSettings.show_system_tables) {
                    const systemTables = ['migrations', 'failed_jobs', 'password_resets'];
                    if (systemTables.some(sys => table.name.includes(sys))) return false;
                }

                // Empty tables filter
                if (!this.filterSettings.show_empty_tables) {
                    if ((table.row_count || 0) === 0) return false;
                }

                // Column count filter
                const columnCount = table.columns?.length || 0;
                if (columnCount < this.filterSettings.min_columns ||
                    columnCount > this.filterSettings.max_columns) return false;

                return true;
            });
        },

        // UI Controls
        toggleSidebar() {
            this.showSidebar = !this.showSidebar;
        },

        toggleGrid() {
            this.showGrid = !this.showGrid;
            this.visualizationEngine?.toggleGrid(this.showGrid);
        },

        toggleSnapToGrid() {
            this.snapToGrid = !this.snapToGrid;
            this.visualizationEngine?.setSnapToGrid(this.snapToGrid);
        },

        toggleMinimap() {
            this.showMinimap = !this.showMinimap;
            this.visualizationEngine?.toggleMinimap(this.showMinimap);
        },

        toggleRelationships() {
            this.showRelationships = !this.showRelationships;
            this.visualizationEngine?.toggleRelationships(this.showRelationships);
            this.$wire.toggleRelationships(this.showRelationships);
        },

        toggleIndexes() {
            this.showIndexes = !this.showIndexes;
            this.visualizationEngine?.toggleIndexes(this.showIndexes);
            this.$wire.toggleIndexes(this.showIndexes);
        },

        // Zoom and View Controls
        zoomIn() {
            this.zoomLevel = Math.min(this.zoomLevel + 10, 200);
            this.visualizationEngine?.setZoom(this.zoomLevel / 100);
        },

        zoomOut() {
            this.zoomLevel = Math.max(this.zoomLevel - 10, 25);
            this.visualizationEngine?.setZoom(this.zoomLevel / 100);
        },

        fitToScreen() {
            this.visualizationEngine?.fitToScreen();
            this.zoomLevel = Math.round(this.visualizationEngine?.getCurrentZoom() * 100);
        },

        resetView() {
            this.selectedTable = null;
            this.selectedTables = [];
            this.searchQuery = '';
            this.zoomLevel = 100;
            this.visualizationEngine?.resetView();
            this.$wire.resetView();
        },

        // Actions
        refreshSchema() {
            this.isLoading = true;
            this.$wire.refreshSchema().then(() => {
                this.isLoading = false;
            });
        },

        applyAutoLayout() {
            this.visualizationEngine?.applyAutoLayout();
            this.$wire.applyAutoLayout();
        },

        exportSchema() {
            this.$wire.openExportModal();
        },

        openSettings() {
            this.$wire.openSettingsModal();
        },

        switchConnection() {
            this.$wire.switchConnection(this.selectedConnection);
        },

        // Table Details
        async loadTableDetails(tableName) {
            try {
                const tableData = this.schemaData.tables?.find(t => t.name === tableName);
                if (tableData) {
                    // Load additional details via Livewire if needed
                    const details = await this.$wire.getTableData(tableName);
                    this.renderTableDetails(details || tableData);
                }
            } catch (error) {
                console.error('Failed to load table details:', error);
            }
        },

        renderTableDetails(tableData) {
            const detailBody = document.querySelector('.detail-body');
            if (!detailBody || !tableData) return;

            // Render based on active tab
            switch (this.activeDetailTab) {
                case 'structure':
                    this.renderTableStructure(detailBody, tableData);
                    break;
                case 'relationships':
                    this.renderTableRelationships(detailBody, tableData);
                    break;
                case 'indexes':
                    this.renderTableIndexes(detailBody, tableData);
                    break;
                case 'data':
                    this.renderTableData(detailBody, tableData);
                    break;
            }
        },

        renderTableStructure(container, tableData) {
            const html = `
                <div class="table-structure">
                    <div class="structure-header">
                        <h4>Columns (${tableData.columns?.length || 0})</h4>
                    </div>
                    <div class="columns-list">
                        ${(tableData.columns || []).map(column => `
                            <div class="column-item">
                                <div class="column-name">
                                    ${column.primary_key ? '🔑 ' : ''}
                                    ${column.is_foreign_key ? '🔗 ' : ''}
                                    <strong>${column.name}</strong>
                                </div>
                                <div class="column-type">${column.type}</div>
                                <div class="column-details">
                                    ${column.nullable ? 'Nullable' : 'Not Null'}
                                    ${column.default ? `, Default: ${column.default}` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            container.innerHTML = html;
        },

        renderTableRelationships(container, tableData) {
            const relationships = this.schemaData.relationships?.filter(rel =>
                rel.from_table === tableData.name || rel.to_table === tableData.name
            ) || [];

            const html = `
                <div class="table-relationships">
                    <div class="relationships-header">
                        <h4>Relationships (${relationships.length})</h4>
                    </div>
                    <div class="relationships-list">
                        ${relationships.map(rel => `
                            <div class="relationship-item">
                                <div class="relationship-type">
                                    ${rel.from_table === tableData.name ? 'References' : 'Referenced by'}
                                </div>
                                <div class="relationship-details">
                                    <strong>${rel.from_table}</strong>.${rel.from_column} 
                                    → <strong>${rel.to_table}</strong>.${rel.to_column}
                                </div>
                                <div class="relationship-actions">
                                    ${rel.on_update ? `On Update: ${rel.on_update}` : ''}
                                    ${rel.on_delete ? `, On Delete: ${rel.on_delete}` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            container.innerHTML = html;
        },

        renderTableIndexes(container, tableData) {
            const html = `
                <div class="table-indexes">
                    <div class="indexes-header">
                        <h4>Indexes (${tableData.indexes?.length || 0})</h4>
                    </div>
                    <div class="indexes-list">
                        ${(tableData.indexes || []).map(index => `
                            <div class="index-item">
                                <div class="index-name">
                                    <strong>${index.name}</strong>
                                    ${index.unique ? '<span class="badge">Unique</span>' : ''}
                                </div>
                                <div class="index-columns">${index.column}</div>
                                <div class="index-type">${index.type || 'btree'}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            container.innerHTML = html;
        },

        renderTableData(container, tableData) {
            const html = `
                <div class="table-data">
                    <div class="data-header">
                        <h4>Table Statistics</h4>
                    </div>
                    <div class="data-stats">
                        <div class="stat-item">
                            <span class="stat-label">Row Count:</span>
                            <span class="stat-value">${this.formatNumber(tableData.row_count || 0)}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Columns:</span>
                            <span class="stat-value">${tableData.columns?.length || 0}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Indexes:</span>
                            <span class="stat-value">${tableData.indexes?.length || 0}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Size:</span>
                            <span class="stat-value">${tableData.size || 'Unknown'}</span>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML = html;
        },

        // Utilities
        formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        },

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        handleKeyboardShortcuts(e) {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 'f':
                        e.preventDefault();
                        document.querySelector('.search-input')?.focus();
                        break;
                    case 'r':
                        e.preventDefault();
                        this.refreshSchema();
                        break;
                    case '1':
                        e.preventDefault();
                        this.switchView('interactive');
                        break;
                    case '2':
                        e.preventDefault();
                        this.switchView('table_detail');
                        break;
                    case '3':
                        e.preventDefault();
                        this.switchView('dependencies');
                        break;
                }
            }

            // Escape key
            if (e.key === 'Escape') {
                this.selectedTable = null;
                this.searchQuery = '';
            }
        },

        updateTablePosition(tableName, position) {
            // Notify Livewire about position change
            this.$wire.updateTablePosition(tableName, position);
        },

        updateViewport(viewport) {
            this.zoomLevel = Math.round(viewport.zoom * 100);
            // Notify Livewire about viewport change
            this.$wire.updateViewport(viewport);
        },

        handleError(message, error) {
            console.error(message, error);

            // Show user-friendly error notification
            const notification = document.createElement('div');
            notification.className = 'error-notification';
            notification.innerHTML = `
                <div class="error-content">
                    <strong>Error:</strong> ${message}
                    <button onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }
}

/**
 * Schema Visualization Engine
 * 
 * Advanced D3.js-based visualization engine for database schemas
 * with interactive features and performance optimizations.
 */
class SchemaVisualizationEngine {
    constructor(options) {
        this.container = options.container;
        this.data = options.data || {};
        this.config = {
            showRelationships: true,
            showIndexes: true,
            snapToGrid: true,
            showGrid: false,
            gridSize: 20,
            ...options.config
        };
        this.callbacks = options.callbacks || {};

        // D3 elements
        this.svg = null;
        this.g = null;
        this.zoom = null;
        this.simulation = null;

        // State
        this.nodes = [];
        this.links = [];
        this.selectedTable = null;
        this.currentZoom = 1;
        this.currentMode = 'interactive';

        // Dimensions
        this.width = 0;
        this.height = 0;

        this.initialize();
    }

    initialize() {
        this.setupContainer();
        this.createSVG();
        this.setupZoom();
        this.setupDefs();
        this.setupEventListeners();
    }

    setupContainer() {
        const container = d3.select(this.container);
        const containerNode = container.node();

        if (!containerNode) {
            throw new Error(`Container ${this.container} not found`);
        }

        this.width = containerNode.clientWidth || 800;
        this.height = containerNode.clientHeight || 600;
    }

    createSVG() {
        const container = d3.select(this.container);
        container.selectAll('*').remove();

        this.svg = container
            .append('svg')
            .attr('width', '100%')
            .attr('height', '100%')
            .attr('viewBox', `0 0 ${this.width} ${this.height}`)
            .style('background', 'var(--schema-bg-secondary)');

        // Create main group for zoom/pan
        this.g = this.svg.append('g').attr('class', 'main-group');

        // Grid layer
        this.gridLayer = this.g.append('g').attr('class', 'grid-layer');

        // Links layer
        this.linksLayer = this.g.append('g').attr('class', 'links-layer');

        // Nodes layer
        this.nodesLayer = this.g.append('g').attr('class', 'nodes-layer');

        // UI layer (always on top)
        this.uiLayer = this.svg.append('g').attr('class', 'ui-layer');
    }

    setupZoom() {
        this.zoom = d3.zoom()
            .scaleExtent([0.1, 3])
            .on('zoom', (event) => {
                this.g.attr('transform', event.transform);
                this.currentZoom = event.transform.k;

                if (this.callbacks.onViewportChange) {
                    this.callbacks.onViewportChange({
                        zoom: this.currentZoom,
                        x: event.transform.x,
                        y: event.transform.y
                    });
                }
            });

        this.svg.call(this.zoom);
    }

    setupDefs() {
        const defs = this.svg.append('defs');

        // Arrow markers for relationships
        defs.append('marker')
            .attr('id', 'arrowhead')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', 8)
            .attr('refY', 0)
            .attr('markerWidth', 6)
            .attr('markerHeight', 6)
            .attr('orient', 'auto')
            .append('path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr('fill', 'var(--schema-primary)');

        // Gradient for table headers
        const gradient = defs.append('linearGradient')
            .attr('id', 'tableHeaderGradient')
            .attr('x1', '0%')
            .attr('y1', '0%')
            .attr('x2', '0%')
            .attr('y2', '100%');

        gradient.append('stop')
            .attr('offset', '0%')
            .attr('stop-color', 'var(--schema-primary)')
            .attr('stop-opacity', 1);

        gradient.append('stop')
            .attr('offset', '100%')
            .attr('stop-color', 'var(--schema-primary-dark)')
            .attr('stop-opacity', 1);
    }

    setupEventListeners() {
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));
    }

    render() {
        this.prepareData();
        this.createGrid();
        this.createLinks();
        this.createNodes();
        this.setupForceSimulation();
    }

    prepareData() {
        const tables = this.data.tables || [];
        const relationships = this.data.relationships || [];

        // Prepare nodes (tables)
        this.nodes = tables.map(table => ({
            id: table.name,
            name: table.name,
            columns: table.columns || [],
            row_count: table.row_count || 0,
            indexes: table.indexes || [],
            x: table.position?.x || Math.random() * this.width,
            y: table.position?.y || Math.random() * this.height,
            fx: null,
            fy: null
        }));

        // Prepare links (relationships)
        this.links = relationships.map(rel => ({
            source: rel.from_table,
            target: rel.to_table,
            from_column: rel.from_column,
            to_column: rel.to_column,
            constraint_name: rel.constraint_name,
            relationship_type: rel.relationship_type
        }));
    }

    createGrid() {
        if (!this.config.showGrid) {
            this.gridLayer.selectAll('*').remove();
            return;
        }

        const gridSize = this.config.gridSize;
        const lines = [];

        // Vertical lines
        for (let x = 0; x <= this.width; x += gridSize) {
            lines.push({
                x1: x, y1: 0, x2: x, y2: this.height,
                class: x % (gridSize * 5) === 0 ? 'grid-line-major' : 'grid-line-minor'
            });
        }

        // Horizontal lines  
        for (let y = 0; y <= this.height; y += gridSize) {
            lines.push({
                x1: 0, y1: y, x2: this.width, y2: y,
                class: y % (gridSize * 5) === 0 ? 'grid-line-major' : 'grid-line-minor'
            });
        }

        this.gridLayer.selectAll('.grid-line')
            .data(lines)
            .join('line')
            .attr('class', d => `grid-line ${d.class}`)
            .attr('x1', d => d.x1)
            .attr('y1', d => d.y1)
            .attr('x2', d => d.x2)
            .attr('y2', d => d.y2)
            .attr('stroke', 'var(--schema-border)')
            .attr('stroke-width', d => d.class === 'grid-line-major' ? 1 : 0.5)
            .attr('opacity', 0.3);
    }

    createLinks() {
        if (!this.config.showRelationships) {
            this.linksLayer.selectAll('*').remove();
            return;
        }

        const linkElements = this.linksLayer.selectAll('.relationship-line')
            .data(this.links)
            .join('path')
            .attr('class', 'relationship-line')
            .attr('stroke', 'var(--schema-primary)')
            .attr('stroke-width', 2)
            .attr('fill', 'none')
            .attr('opacity', 0.7)
            .attr('marker-end', 'url(#arrowhead)')
            .on('mouseover', this.handleLinkHover.bind(this))
            .on('mouseout', this.handleLinkLeave.bind(this));

        this.linkElements = linkElements;
    }

    createNodes() {
        const nodeGroups = this.nodesLayer.selectAll('.table-node')
            .data(this.nodes)
            .join('g')
            .attr('class', 'table-node')
            .attr('cursor', 'pointer')
            .call(this.createDragBehavior())
            .on('click', this.handleNodeClick.bind(this))
            .on('dblclick', this.handleNodeDoubleClick.bind(this));

        // Create table visual representation
        nodeGroups.each((d, i, nodes) => {
            this.renderTableNode(d3.select(nodes[i]), d);
        });

        this.nodeElements = nodeGroups;
    }

    renderTableNode(nodeGroup, data) {
        const tableWidth = 200;
        const headerHeight = 30;
        const rowHeight = 20;
        const tableHeight = headerHeight + (data.columns.length * rowHeight);

        // Clear existing content
        nodeGroup.selectAll('*').remove();

        // Table container
        const tableContainer = nodeGroup.append('g')
            .attr('class', 'table-container');

        // Table shadow
        tableContainer.append('rect')
            .attr('class', 'table-shadow')
            .attr('x', 2)
            .attr('y', 2)
            .attr('width', tableWidth)
            .attr('height', tableHeight)
            .attr('rx', 4)
            .attr('fill', 'rgba(0, 0, 0, 0.1)');

        // Table body
        tableContainer.append('rect')
            .attr('class', 'table-body')
            .attr('x', 0)
            .attr('y', 0)
            .attr('width', tableWidth)
            .attr('height', tableHeight)
            .attr('rx', 4)
            .attr('fill', 'var(--schema-bg-primary)')
            .attr('stroke', 'var(--schema-border)')
            .attr('stroke-width', 1);

        // Table header
        tableContainer.append('rect')
            .attr('class', 'table-header')
            .attr('x', 0)
            .attr('y', 0)
            .attr('width', tableWidth)
            .attr('height', headerHeight)
            .attr('rx', 4)
            .attr('fill', 'url(#tableHeaderGradient)');

        // Table name
        tableContainer.append('text')
            .attr('class', 'table-name')
            .attr('x', tableWidth / 2)
            .attr('y', headerHeight / 2)
            .attr('dy', '0.35em')
            .attr('text-anchor', 'middle')
            .attr('fill', 'white')
            .attr('font-weight', '600')
            .attr('font-size', '12px')
            .text(data.name);

        // Row count badge
        if (data.row_count > 0) {
            const badge = tableContainer.append('g')
                .attr('class', 'row-count-badge')
                .attr('transform', `translate(${tableWidth - 25}, 5)`);

            badge.append('rect')
                .attr('width', 20)
                .attr('height', 16)
                .attr('rx', 8)
                .attr('fill', 'var(--schema-warning)')
                .attr('opacity', 0.9);

            badge.append('text')
                .attr('x', 10)
                .attr('y', 8)
                .attr('dy', '0.35em')
                .attr('text-anchor', 'middle')
                .attr('fill', 'white')
                .attr('font-size', '9px')
                .attr('font-weight', '600')
                .text(this.formatRowCount(data.row_count));
        }

        // Columns
        data.columns.forEach((column, index) => {
            const y = headerHeight + (index * rowHeight);

            // Column row background (alternate colors)
            if (index % 2 === 1) {
                tableContainer.append('rect')
                    .attr('x', 1)
                    .attr('y', y)
                    .attr('width', tableWidth - 2)
                    .attr('height', rowHeight)
                    .attr('fill', 'var(--schema-bg-secondary)')
                    .attr('opacity', 0.5);
            }

            // Column indicators
            let indicatorX = 8;

            if (column.primary_key) {
                tableContainer.append('text')
                    .attr('x', indicatorX)
                    .attr('y', y + rowHeight / 2)
                    .attr('dy', '0.35em')
                    .attr('fill', 'var(--schema-warning)')
                    .attr('font-size', '10px')
                    .text('🔑');
                indicatorX += 15;
            }

            if (column.is_foreign_key) {
                tableContainer.append('text')
                    .attr('x', indicatorX)
                    .attr('y', y + rowHeight / 2)
                    .attr('dy', '0.35em')
                    .attr('fill', 'var(--schema-info)')
                    .attr('font-size', '10px')
                    .text('🔗');
                indicatorX += 15;
            }

            // Column name
            tableContainer.append('text')
                .attr('class', 'column-name')
                .attr('x', indicatorX)
                .attr('y', y + rowHeight / 2)
                .attr('dy', '0.35em')
                .attr('fill', 'var(--schema-text-primary)')
                .attr('font-size', '10px')
                .attr('font-weight', column.primary_key ? '600' : '400')
                .text(this.truncateText(column.name, 15));

            // Column type
            tableContainer.append('text')
                .attr('class', 'column-type')
                .attr('x', tableWidth - 8)
                .attr('y', y + rowHeight / 2)
                .attr('dy', '0.35em')
                .attr('text-anchor', 'end')
                .attr('fill', 'var(--schema-text-muted)')
                .attr('font-size', '9px')
                .text(this.truncateText(column.type, 10));
        });

        // Store dimensions for positioning
        data.width = tableWidth;
        data.height = tableHeight;
    }

    setupForceSimulation() {
        this.simulation = d3.forceSimulation(this.nodes)
            .force('link', d3.forceLink(this.links)
                .id(d => d.id)
                .distance(150)
                .strength(0.5))
            .force('charge', d3.forceManyBody()
                .strength(-1000)
                .distanceMax(300))
            .force('center', d3.forceCenter(this.width / 2, this.height / 2))
            .force('collision', d3.forceCollide()
                .radius(d => Math.max(d.width, d.height) / 2 + 10))
            .on('tick', this.handleTick.bind(this))
            .on('end', this.handleSimulationEnd.bind(this));

        // Let simulation settle quickly
        this.simulation.alpha(0.3).restart();
    }

    handleTick() {
        // Update node positions
        this.nodeElements
            .attr('transform', d => `translate(${d.x - d.width / 2}, ${d.y - d.height / 2})`);

        // Update link paths
        this.linkElements
            .attr('d', this.calculateLinkPath.bind(this));
    }

    handleSimulationEnd() {
        // Simulation finished - save positions if needed
        console.log('✅ Force simulation completed');
    }

    calculateLinkPath(d) {
        const source = d.source;
        const target = d.target;

        if (!source || !target) return '';

        // Calculate connection points on table edges
        const sourcePoint = this.getConnectionPoint(source, target);
        const targetPoint = this.getConnectionPoint(target, source);

        // Create curved path
        const midX = (sourcePoint.x + targetPoint.x) / 2;
        const midY = (sourcePoint.y + targetPoint.y) / 2;

        // Control points for curve
        const dx = targetPoint.x - sourcePoint.x;
        const dy = targetPoint.y - sourcePoint.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        const controlOffset = Math.min(distance * 0.3, 50);
        const controlX1 = sourcePoint.x + (dy / distance) * controlOffset;
        const controlY1 = sourcePoint.y - (dx / distance) * controlOffset;
        const controlX2 = targetPoint.x + (dy / distance) * controlOffset;
        const controlY2 = targetPoint.y - (dx / distance) * controlOffset;

        return `M ${sourcePoint.x} ${sourcePoint.y} 
                C ${controlX1} ${controlY1}, 
                  ${controlX2} ${controlY2}, 
                  ${targetPoint.x} ${targetPoint.y}`;
    }

    getConnectionPoint(table, otherTable) {
        const tableX = table.x;
        const tableY = table.y;
        const tableWidth = table.width || 200;
        const tableHeight = table.height || 100;

        const otherX = otherTable.x;
        const otherY = otherTable.y;

        // Calculate which edge to connect to
        const dx = otherX - tableX;
        const dy = otherY - tableY;

        if (Math.abs(dx) > Math.abs(dy)) {
            // Connect to left or right edge
            return {
                x: tableX + (dx > 0 ? tableWidth : 0),
                y: tableY + tableHeight / 2
            };
        } else {
            // Connect to top or bottom edge
            return {
                x: tableX + tableWidth / 2,
                y: tableY + (dy > 0 ? tableHeight : 0)
            };
        }
    }

    createDragBehavior() {
        return d3.drag()
            .on('start', (event, d) => {
                if (!event.active) this.simulation.alphaTarget(0.3).restart();
                d.fx = d.x;
                d.fy = d.y;

                if (this.callbacks.onTableDragStart) {
                    this.callbacks.onTableDragStart(d);
                }
            })
            .on('drag', (event, d) => {
                d.fx = event.x;
                d.fy = event.y;

                // Snap to grid if enabled
                if (this.config.snapToGrid) {
                    const gridSize = this.config.gridSize;
                    d.fx = Math.round(d.fx / gridSize) * gridSize;
                    d.fy = Math.round(d.fy / gridSize) * gridSize;
                }

                if (this.callbacks.onTableDrag) {
                    this.callbacks.onTableDrag(d);
                }
            })
            .on('end', (event, d) => {
                if (!event.active) this.simulation.alphaTarget(0);
                d.fx = null;
                d.fy = null;

                if (this.callbacks.onTableDragEnd) {
                    this.callbacks.onTableDragEnd(d);
                }
            });
    }

    handleNodeClick(event, d) {
        event.stopPropagation();
        this.selectTable(d);

        if (this.callbacks.onTableSelect) {
            this.callbacks.onTableSelect(d);
        }
    }

    handleNodeDoubleClick(event, d) {
        event.stopPropagation();

        if (this.callbacks.onTableDoubleClick) {
            this.callbacks.onTableDoubleClick(d);
        }
    }

    handleLinkHover(event, d) {
        // Highlight the relationship
        d3.select(event.target)
            .attr('stroke-width', 3)
            .attr('opacity', 1);

        // Show tooltip
        this.showTooltip(event, {
            title: `${d.source.name} → ${d.target.name}`,
            content: `${d.from_column} → ${d.to_column}`,
            type: d.relationship_type
        });
    }

    handleLinkLeave(event, d) {
        // Reset relationship appearance
        d3.select(event.target)
            .attr('stroke-width', 2)
            .attr('opacity', 0.7);

        this.hideTooltip();
    }

    selectTable(table) {
        this.selectedTable = table;

        // Update visual selection
        this.nodeElements.classed('selected', d => d.id === table.id);

        // Highlight related tables
        const relatedTables = new Set();
        this.links.forEach(link => {
            if (link.source.id === table.id) {
                relatedTables.add(link.target.id);
            }
            if (link.target.id === table.id) {
                relatedTables.add(link.source.id);
            }
        });

        this.nodeElements.classed('related', d => relatedTables.has(d.id));
        this.linkElements.classed('highlighted', d =>
            d.source.id === table.id || d.target.id === table.id
        );
    }

    showTooltip(event, content) {
        let tooltip = this.uiLayer.select('.tooltip');

        if (tooltip.empty()) {
            tooltip = this.uiLayer.append('g')
                .attr('class', 'tooltip')
                .style('pointer-events', 'none');

            tooltip.append('rect')
                .attr('class', 'tooltip-bg')
                .attr('rx', 4)
                .attr('fill', 'var(--schema-bg-primary)')
                .attr('stroke', 'var(--schema-border)')
                .attr('stroke-width', 1);

            tooltip.append('text')
                .attr('class', 'tooltip-text')
                .attr('fill', 'var(--schema-text-primary)')
                .attr('font-size', '11px');
        }

        const text = tooltip.select('.tooltip-text');
        text.text(`${content.title}: ${content.content}`);

        const bbox = text.node().getBBox();
        const padding = 8;

        tooltip.select('.tooltip-bg')
            .attr('x', bbox.x - padding)
            .attr('y', bbox.y - padding)
            .attr('width', bbox.width + padding * 2)
            .attr('height', bbox.height + padding * 2);

        const [mouseX, mouseY] = d3.pointer(event, this.svg.node());
        tooltip.attr('transform', `translate(${mouseX + 10}, ${mouseY - 10})`);

        tooltip.transition()
            .duration(200)
            .style('opacity', 1);
    }

    hideTooltip() {
        this.uiLayer.select('.tooltip')
            .transition()
            .duration(200)
            .style('opacity', 0);
    }

    handleResize() {
        const containerNode = d3.select(this.container).node();
        this.width = containerNode.clientWidth || 800;
        this.height = containerNode.clientHeight || 600;

        this.svg.attr('viewBox', `0 0 ${this.width} ${this.height}`);

        if (this.simulation) {
            this.simulation
                .force('center', d3.forceCenter(this.width / 2, this.height / 2))
                .alpha(0.1)
                .restart();
        }

        this.createGrid();
    }

    // Utility methods
    formatRowCount(count) {
        if (count >= 1000000) {
            return Math.round(count / 1000000) + 'M';
        } else if (count >= 1000) {
            return Math.round(count / 1000) + 'K';
        }
        return count.toString();
    }

    truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength - 3) + '...';
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Public API methods
    updateData(newData) {
        this.data = newData;
        this.render();
    }

    updateConfig(newConfig) {
        this.config = { ...this.config, ...newConfig };

        // Reapply configuration-dependent elements
        this.createGrid();

        if ('showRelationships' in newConfig) {
            this.createLinks();
        }
    }

    centerView() {
        const transform = d3.zoomIdentity
            .translate(this.width / 2, this.height / 2)
            .scale(1);

        this.svg.transition()
            .duration(750)
            .call(this.zoom.transform, transform);
    }

    fitToContent() {
        if (this.nodes.length === 0) return;

        const bounds = this.getContentBounds();
        const fullWidth = bounds.maxX - bounds.minX;
        const fullHeight = bounds.maxY - bounds.minY;

        const scale = Math.min(
            this.width / (fullWidth + 100),
            this.height / (fullHeight + 100),
            2
        );

        const centerX = bounds.minX + fullWidth / 2;
        const centerY = bounds.minY + fullHeight / 2;

        const transform = d3.zoomIdentity
            .translate(this.width / 2, this.height / 2)
            .scale(scale)
            .translate(-centerX, -centerY);

        this.svg.transition()
            .duration(750)
            .call(this.zoom.transform, transform);
    }

    getContentBounds() {
        let minX = Infinity, maxX = -Infinity;
        let minY = Infinity, maxY = -Infinity;

        this.nodes.forEach(node => {
            const nodeMinX = node.x - (node.width || 200) / 2;
            const nodeMaxX = node.x + (node.width || 200) / 2;
            const nodeMinY = node.y - (node.height || 100) / 2;
            const nodeMaxY = node.y + (node.height || 100) / 2;

            minX = Math.min(minX, nodeMinX);
            maxX = Math.max(maxX, nodeMaxX);
            minY = Math.min(minY, nodeMinY);
            maxY = Math.max(maxY, nodeMaxY);
        });

        return { minX, maxX, minY, maxY };
    }

    exportPositions() {
        return this.nodes.map(node => ({
            name: node.name,
            position: { x: node.x, y: node.y }
        }));
    }

    importPositions(positions) {
        positions.forEach(pos => {
            const node = this.nodes.find(n => n.name === pos.name);
            if (node) {
                node.x = pos.position.x;
                node.y = pos.position.y;
                node.fx = pos.position.x;
                node.fy = pos.position.y;
            }
        });

        if (this.simulation) {
            this.simulation.alpha(0.1).restart();
        }
    }

    destroy() {
        if (this.simulation) {
            this.simulation.stop();
        }

        window.removeEventListener('resize', this.handleResize);

        d3.select(this.container).selectAll('*').remove();
    }
}

// Export for global access
window.SchemaVisualizationEngine = SchemaVisualizationEngine;
