<x-filament-panels::page>
    <div x-data="schemaDesigner()" x-init="init()">
        <!-- Header Controls -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex gap-2">
                    <x-filament::button size="sm" color="primary" @click="mode = 'designer'"
                        x-bind:class="{ 'ring-2': mode === 'designer' }">
                        Designer
                    </x-filament::button>
                    <x-filament::button size="sm" color="gray" @click="mode = 'tables'"
                        x-bind:class="{ 'ring-2': mode === 'tables' }">
                        Tables
                    </x-filament::button>
                    <x-filament::button size="sm" color="gray" @click="mode = 'dependencies'"
                        x-bind:class="{ 'ring-2': mode === 'dependencies' }">
                        Dependencies
                    </x-filament::button>
                    <x-filament::button size="sm" color="gray" @click="mode = 'performance'"
                        x-bind:class="{ 'ring-2': mode === 'performance' }">
                        Performance
                    </x-filament::button>
                    <x-filament::button size="sm" color="gray" @click="mode = 'matrix'"
                        x-bind:class="{ 'ring-2': mode === 'matrix' }">
                        Matrix
                    </x-filament::button>
                </div>

                <div class="flex gap-2">
                    <x-filament::button size="sm" color="gray" icon="heroicon-o-magnifying-glass-minus"
                        @click="zoomOut()" />
                    <span class="px-3 py-1 text-sm" x-text="Math.round(zoom * 100) + '%'"></span>
                    <x-filament::button size="sm" color="gray" icon="heroicon-o-magnifying-glass-plus"
                        @click="zoomIn()" />
                    <x-filament::button size="sm" color="gray" icon="heroicon-o-arrows-pointing-in"
                        @click="fitToScreen()">
                        Fit
                    </x-filament::button>
                    <x-filament::button size="sm" color="success" icon="heroicon-o-arrow-down-tray"
                        @click="saveSchema()">
                        Save
                    </x-filament::button>
                    <x-filament::button size="sm" color="warning" icon="heroicon-o-document-arrow-down"
                        @click="exportMigration()">
                        Export Migration
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-12 gap-6" style="margin-top: 2vh;">
            <!-- Sidebar -->
            <div class="col-span-3 space-y-4">
                <!-- Connection Information -->
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-server class="w-5 h-5 text-success-500" />
                            Database Connection
                        </div>
                    </x-slot>

                    <div class="space-y-3">
                        <div class="p-3 bg-success-50 border border-success-200 rounded-lg">
                            <div class="text-sm font-medium text-success-800">
                                {{ $this->connectionDisplay }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            @foreach($this->connectionDetails as $label => $value)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $label }}:</span>
                                    <span class="font-medium text-gray-900">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-filament::section>

                <!-- Tables -->
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-table-cells class="w-5 h-5 text-primary-500" />
                                Tables
                            </div>
                            <span class="text-xs text-gray-500" x-text="filteredTables.length + ' tables'"></span>
                        </div>
                    </x-slot>

                    <div class="mb-4 p-2 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <x-heroicon-o-information-circle class="w-4 h-4 inline mr-1" />
                                Showing user tables from <strong>{{ $this->connectionInfo['database'] }}</strong>
                                database
                                <br><small class="text-blue-600">System tables and plugin tables are automatically
                                    filtered out</small>
                            </div>
                            <div class="flex gap-1">
                                <x-filament::button size="xs" color="gray" icon="heroicon-o-bug-ant"
                                    wire:click="debugTableInfo" title="Debug table info">
                                    Debug
                                </x-filament::button>
                                <x-filament::button size="xs" color="warning" icon="heroicon-o-arrow-up-tray"
                                    wire:click="updateFrontend" title="Update frontend">
                                    Update
                                </x-filament::button>
                                <x-filament::button size="xs" color="primary" icon="heroicon-o-arrow-path"
                                    wire:click="refreshSchema" class="ml-1">
                                    Refresh
                                </x-filament::button>
                            </div>
                        </div>
                    </div>

                    <x-filament::input.wrapper class="mb-4">
                        <x-filament::input type="text" placeholder="Search tables..." x-model="searchQuery"
                            @input="filterTables()" />
                    </x-filament::input.wrapper>

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        <template x-for="table in filteredTables" :key="table.name">
                            <div class="p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors"
                                x-bind:class="{ 'bg-primary-50 border-primary-500': selectedTable === table.name }"
                                @click="selectTable(table)">
                                <div class="font-medium text-sm" x-text="table.name"></div>
                                <div class="text-xs text-gray-500">
                                    <span x-text="table.columns.length"></span> columns
                                </div>
                            </div>
                        </template>
                    </div>

                    <x-filament::button class="w-full mt-4" style="margin-top: 2vh;" size="sm" @click="addNewTable()">
                        Add New Table
                    </x-filament::button>
                </x-filament::section>

                <!-- Version History -->
                <x-filament::section>
                    <x-slot name="heading">
                        Version History
                    </x-slot>

                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($this->versionHistory as $version)
                            <div class="p-2 text-sm rounded border cursor-pointer hover:bg-gray-50"
                                wire:click="loadVersion({{ $version['id'] }})">
                                <div class="font-medium">{{ $version['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $version['created_at'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            </div>

            <!-- Canvas Area -->
            <div class="col-span-9">
                <div class="relative bg-white rounded-lg border border-gray-200 overflow-hidden" style="height: 700px;">
                    <!-- DrawFlow Canvas -->
                    <div id="drawflow" class="absolute inset-0"></div>

                    <!-- Minimap -->
                    <div x-show="showMinimap"
                        class="absolute bottom-4 right-4 w-48 h-32 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden">
                        <div id="minimap" class="w-full h-full"></div>
                    </div>

                    <!-- Grid Toggle -->
                    <div class="absolute top-4 right-4 flex gap-2">
                        <x-filament::button size="xs" color="gray" @click="toggleGrid()"
                            x-bind:class="{ 'bg-primary-500': showGrid }">
                            Grid
                        </x-filament::button>
                        <x-filament::button size="xs" color="gray" @click="toggleMinimap()"
                            x-bind:class="{ 'bg-primary-500': showMinimap }">
                            Minimap
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Editor Modal -->
        <div x-show="showTableEditor" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="closeTableEditor()">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="closeTableEditor()"></div>

                <div class="relative bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-medium">Edit Table: <span x-text="editingTable?.name"></span></h2>
                    </div>

                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <!-- Table Name -->
                        <div class="mb-6">
                            <x-filament::input.wrapper>
                                <x-slot name="label">Table Name</x-slot>
                                <x-filament::input type="text" x-model="editingTable.name" />
                            </x-filament::input.wrapper>
                        </div>

                        <!-- Columns -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium mb-4">Columns</h3>

                            <div class="space-y-4">
                                <template x-for="(column, index) in editingTable.columns" :key="index">
                                    <div class="flex gap-2 items-start p-4 border rounded-lg">
                                        <div class="flex-1 grid grid-cols-4 gap-2">
                                            <x-filament::input.wrapper>
                                                <x-slot name="label">Name</x-slot>
                                                <x-filament::input type="text" x-model="column.name" />
                                            </x-filament::input.wrapper>

                                            <x-filament::input.wrapper>
                                                <x-slot name="label">Type</x-slot>
                                                <x-filament::input.select x-model="column.type">
                                                    <option value="bigint">Big Integer</option>
                                                    <option value="int">Integer</option>
                                                    <option value="varchar">String</option>
                                                    <option value="text">Text</option>
                                                    <option value="datetime">DateTime</option>
                                                    <option value="date">Date</option>
                                                    <option value="boolean">Boolean</option>
                                                    <option value="decimal">Decimal</option>
                                                    <option value="json">JSON</option>
                                                </x-filament::input.select>
                                            </x-filament::input.wrapper>

                                            <x-filament::input.wrapper>
                                                <x-slot name="label">Default</x-slot>
                                                <x-filament::input type="text" x-model="column.default"
                                                    placeholder="NULL" />
                                            </x-filament::input.wrapper>

                                            <div class="space-y-2">
                                                <label class="flex items-center gap-2">
                                                    <x-filament::input.checkbox x-model="column.nullable" />
                                                    <span class="text-sm">Nullable</span>
                                                </label>
                                                <label class="flex items-center gap-2">
                                                    <x-filament::input.checkbox x-model="column.unique" />
                                                    <span class="text-sm">Unique</span>
                                                </label>
                                                <label class="flex items-center gap-2">
                                                    <x-filament::input.checkbox x-model="column.autoIncrement" />
                                                    <span class="text-sm">Auto Increment</span>
                                                </label>
                                            </div>
                                        </div>

                                        <x-filament::button size="sm" color="danger" icon="heroicon-o-trash"
                                            @click="removeColumn(index)" />
                                    </div>
                                </template>
                            </div>

                            <x-filament::button class="mt-4" size="sm" @click="addColumn()">
                                Add Column
                            </x-filament::button>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t flex justify-end gap-2">
                        <x-filament::button color="gray" @click="closeTableEditor()">
                            Cancel
                        </x-filament::button>
                        <x-filament::button color="primary" @click="saveTableChanges()">
                            Save Changes
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/drawflow@0.0.59/dist/drawflow.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/drawflow@0.0.59/dist/drawflow.min.css">

        <style>
            /* Custom styles for DrawFlow integration with Filament */
            #drawflow {
                background-size: 20px 20px;
                background-image:
                    linear-gradient(to right, #f3f4f6 1px, transparent 1px),
                    linear-gradient(to bottom, #f3f4f6 1px, transparent 1px);
            }

            .drawflow-node {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                min-width: 200px;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }

            .drawflow-node.selected {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .drawflow-node .title {
                background: #f9fafb;
                padding: 0.75rem;
                border-bottom: 1px solid #e5e7eb;
                font-weight: 500;
                border-radius: 0.5rem 0.5rem 0 0;
            }

            .drawflow-node .content {
                padding: 0.75rem;
            }

            .drawflow-node .column-item {
                padding: 0.25rem 0;
                font-size: 0.875rem;
                color: #4b5563;
            }

            .drawflow-node .column-item.primary {
                color: #dc2626;
                font-weight: 500;
            }

            .drawflow-node .column-item.foreign {
                color: #2563eb;
            }

            .drawflow .connection {
                stroke: #3b82f6;
                stroke-width: 3;
            }

            .drawflow .connection.selected {
                stroke: #dc2626;
            }

            /* Hide grid when disabled */
            #drawflow.no-grid {
                background-image: none;
                background-color: #fafafa;
            }
        </style>

        <script>
            function schemaDesigner() {
                return {
                    editor: null,
                    mode: 'designer',
                    zoom: 1,
                    showGrid: true,
                    showMinimap: false,
                    searchQuery: '',
                    tables: @json($this->schemaData['tables'] ?? []),
                    relationships: @json($this->schemaData['relationships'] ?? []),
                    filteredTables: [],
                    selectedTable: null,
                    showTableEditor: false,
                    editingTable: null,
                    nodePositions: {},

                    init() {
                        console.log('Initializing Schema Designer...');
                        console.log('Initial tables:', this.tables.length);
                        console.log('Initial relationships:', this.relationships.length);

                        this.initDrawFlow();
                        this.filterTables(); // Only filter initially, don't load schema yet

                        // Listen for Livewire events
                        window.addEventListener('schema-loaded', (event) => {
                            console.log('Schema loaded event received:', event.detail);

                            // Simple data extraction - Livewire passes data directly in event.detail
                            const data = event.detail || {};
                            this.tables = data.tables || [];
                            this.relationships = data.relationships || [];

                            console.log('Updated tables count:', this.tables.length);
                            console.log('Updated relationships count:', this.relationships.length);

                            // Clear the node positions to force fresh positioning
                            this.nodePositions = {};

                            // Reload the visual schema (canvas)
                            console.log('Reloading canvas...');
                            this.loadSchema();

                            // Update the filtered tables for the sidebar
                            console.log('Filtering tables...');
                            this.filterTables();

                            console.log('Schema update complete.');
                        });

                        window.addEventListener('migration-generated', (event) => {
                            console.log('Migration generated event received:', event.detail);

                            // Check if event data is valid
                            if (!event.detail || !event.detail.content) {
                                console.error('Invalid migration data received:', event.detail);
                                return;
                            }

                            // Download the migration content
                            const blob = new Blob([event.detail.content], { type: 'text/plain' });
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = event.detail.filename || 'migration.php';
                            a.click();
                            window.URL.revokeObjectURL(url);
                        });

                        // Load initial schema after component is ready
                        this.$nextTick(() => {
                            console.log('Loading initial schema...');
                            this.loadSchema();
                        });
                    },

                    initDrawFlow() {
                        const container = document.getElementById('drawflow');
                        this.editor = new Drawflow(container);

                        this.editor.reroute = true;
                        this.editor.reroute_fix_curvature = true;
                        this.editor.force_first_input = false;
                        this.editor.line_path = 5;
                        this.editor.curvature = 0.5;

                        this.editor.start();

                        // Event listeners
                        this.editor.on('nodeCreated', (id) => {
                            console.log('Node created', id);
                        });

                        this.editor.on('nodeRemoved', (id) => {
                            console.log('Node removed', id);
                        });

                        this.editor.on('nodeSelected', (id) => {
                            const nodeData = this.editor.getNodeFromId(id);
                            const tableName = nodeData.data.table;
                            const table = this.tables.find(t => t.name === tableName);
                            if (table) {
                                this.selectTable(table);
                            }
                        });

                        this.editor.on('nodeUnselected', () => {
                            this.selectedTable = null;
                        });

                        this.editor.on('connectionCreated', (connection) => {
                            this.handleConnectionCreated(connection);
                        });

                        this.editor.on('zoom', (zoom) => {
                            this.zoom = zoom;
                        });

                        // Double click to edit
                        container.addEventListener('dblclick', (e) => {
                            if (e.target.closest('.drawflow-node')) {
                                const nodeElement = e.target.closest('.drawflow-node');
                                const nodeId = nodeElement.id.replace('node-', '');
                                const nodeData = this.editor.getNodeFromId(nodeId);
                                const tableName = nodeData.data.table;
                                const table = this.tables.find(t => t.name === tableName);
                                if (table) {
                                    this.editTable(table);
                                }
                            }
                        });
                    },

                    loadSchema() {
                        console.log('Loading schema to canvas. Tables:', this.tables.length, 'Relationships:', this.relationships.length);

                        this.editor.clear();

                        // Add nodes for each table
                        this.tables.forEach((table, index) => {
                            const x = table.position?.x || (200 + (index % 4) * 300);
                            const y = table.position?.y || (100 + Math.floor(index / 4) * 250);

                            const nodeHtml = this.createTableNode(table);
                            const nodeId = this.editor.addNode(
                                'table',
                                1, // inputs
                                1, // outputs
                                x,
                                y,
                                'table-node',
                                { table: table.name },
                                nodeHtml
                            );

                            this.nodePositions[table.name] = nodeId;
                            console.log(`Added table node: ${table.name} (ID: ${nodeId})`);
                        });

                        // Add connections for relationships
                        this.relationships.forEach(rel => {
                            const fromNodeId = this.nodePositions[rel.from];
                            const toNodeId = this.nodePositions[rel.to];

                            if (fromNodeId && toNodeId) {
                                try {
                                    this.editor.addConnection(
                                        fromNodeId,
                                        toNodeId,
                                        'output_1',
                                        'input_1'
                                    );
                                    console.log(`Added relationship: ${rel.from} -> ${rel.to}`);
                                } catch (e) {
                                    console.error('Failed to add connection', e);
                                }
                            } else {
                                console.warn(`Skipped relationship ${rel.from} -> ${rel.to}: missing nodes`);
                            }
                        });

                        console.log('Canvas loading complete. Total nodes:', Object.keys(this.nodePositions).length);
                    },

                    createTableNode(table) {
                        console.log('Creating node for table:', table.name, 'with columns:', table.columns.length);

                        let columnsHtml = '';
                        table.columns.forEach(column => {
                            let className = 'column-item';
                            let icon = '';

                            if (column.name === 'id' && column.autoIncrement) {
                                className += ' primary';
                                icon = '🔑 ';
                            } else if (column.name.endsWith('_id')) {
                                className += ' foreign';
                                icon = '🔗 ';
                            }

                            columnsHtml += `<div class="${className}">${icon}${column.name}: ${column.type}</div>`;
                        });

                        const nodeHtml = `
                                                <div class="title">${table.name}</div>
                                                <div class="content">
                                                    ${columnsHtml}
                                                </div>
                                            `;

                        console.log('Generated node HTML for:', table.name);
                        return nodeHtml;
                    },

                    filterTables() {
                        console.log('Filtering tables. Total tables:', this.tables.length);
                        console.log('Search query:', this.searchQuery);

                        if (!this.searchQuery) {
                            this.filteredTables = this.tables;
                        } else {
                            const query = this.searchQuery.toLowerCase();
                            this.filteredTables = this.tables.filter(table =>
                                table.name.toLowerCase().includes(query) ||
                                table.columns.some(col => col.name.toLowerCase().includes(query))
                            );
                        }

                        console.log('Filtered tables count:', this.filteredTables.length);
                    },

                    selectTable(table) {
                        this.selectedTable = table.name;
                    },

                    addNewTable() {
                        const newTable = @json($this->createDefaultTable());
                        newTable.name = 'new_table_' + Date.now();
                        newTable.position = { x: 400, y: 300 };

                        this.tables.push(newTable);
                        this.filterTables();

                        // Add to DrawFlow
                        const nodeHtml = this.createTableNode(newTable);
                        const nodeId = this.editor.addNode(
                            'table',
                            1,
                            1,
                            newTable.position.x,
                            newTable.position.y,
                            'table-node',
                            { table: newTable.name },
                            nodeHtml
                        );

                        this.nodePositions[newTable.name] = nodeId;

                        // Open editor
                        this.editTable(newTable);
                    },

                    editTable(table) {
                        this.editingTable = JSON.parse(JSON.stringify(table));
                        this.showTableEditor = true;
                    },

                    closeTableEditor() {
                        this.showTableEditor = false;
                        this.editingTable = null;
                    },

                    addColumn() {
                        this.editingTable.columns.push({
                            name: '',
                            type: 'varchar',
                            nullable: true,
                            default: null,
                            autoIncrement: false,
                            unique: false
                        });
                    },

                    removeColumn(index) {
                        this.editingTable.columns.splice(index, 1);
                    },

                    saveTableChanges() {
                        const index = this.tables.findIndex(t => t.name === this.selectedTable);
                        if (index !== -1) {
                            // Update table name in relationships if changed
                            if (this.tables[index].name !== this.editingTable.name) {
                                this.relationships.forEach(rel => {
                                    if (rel.from === this.tables[index].name) {
                                        rel.from = this.editingTable.name;
                                    }
                                    if (rel.to === this.tables[index].name) {
                                        rel.to = this.editingTable.name;
                                    }
                                });
                            }

                            this.tables[index] = JSON.parse(JSON.stringify(this.editingTable));
                            this.filterTables();

                            // Update node in DrawFlow
                            const nodeId = this.nodePositions[this.selectedTable];
                            if (nodeId) {
                                const nodeData = this.editor.getNodeFromId(nodeId);
                                nodeData.data.table = this.editingTable.name;
                                nodeData.html = this.createTableNode(this.editingTable);
                                this.editor.updateNodeDataFromId(nodeId, nodeData);

                                // Update position mapping
                                delete this.nodePositions[this.selectedTable];
                                this.nodePositions[this.editingTable.name] = nodeId;
                            }
                        }

                        this.closeTableEditor();
                    },

                    handleConnectionCreated(connection) {
                        // Get node data
                        const outputNode = this.editor.getNodeFromId(connection.output_id);
                        const inputNode = this.editor.getNodeFromId(connection.input_id);

                        const fromTable = outputNode.data.table;
                        const toTable = inputNode.data.table;

                        // Check if relationship already exists
                        const exists = this.relationships.some(rel =>
                            rel.from === fromTable && rel.to === toTable
                        );

                        if (!exists) {
                            // Add foreign key column to the "from" table
                            const fromTableObj = this.tables.find(t => t.name === fromTable);
                            const toTableObj = this.tables.find(t => t.name === toTable);

                            if (fromTableObj && toTableObj) {
                                const foreignKeyName = toTable.replace(/s$/, '') + '_id';

                                // Check if foreign key already exists
                                if (!fromTableObj.columns.some(col => col.name === foreignKeyName)) {
                                    fromTableObj.columns.push({
                                        name: foreignKeyName,
                                        type: 'bigint',
                                        nullable: true,
                                        default: null,
                                        autoIncrement: false,
                                        unique: false
                                    });
                                }

                                // Add relationship
                                this.relationships.push({
                                    from: fromTable,
                                    to: toTable,
                                    fromColumn: foreignKeyName,
                                    toColumn: 'id',
                                    type: 'belongsTo'
                                });

                                // Update node display
                                outputNode.html = this.createTableNode(fromTableObj);
                                this.editor.updateNodeDataFromId(connection.output_id, outputNode);
                            }
                        }
                    },

                    toggleGrid() {
                        this.showGrid = !this.showGrid;
                        const canvas = document.getElementById('drawflow');
                        if (this.showGrid) {
                            canvas.classList.remove('no-grid');
                        } else {
                            canvas.classList.add('no-grid');
                        }
                    },

                    toggleMinimap() {
                        this.showMinimap = !this.showMinimap;
                    },

                    zoomIn() {
                        this.editor.zoom_in();
                    },

                    zoomOut() {
                        this.editor.zoom_out();
                    },

                    fitToScreen() {
                        this.editor.zoom_reset();
                    },

                    saveSchema() {
                        // Update positions
                        const nodes = this.editor.export().drawflow.Home.data;
                        Object.entries(nodes).forEach(([nodeId, node]) => {
                            const tableName = node.data.table;
                            const table = this.tables.find(t => t.name === tableName);
                            if (table) {
                                table.position = {
                                    x: node.pos_x,
                                    y: node.pos_y
                                };
                            }
                        });

                        const schemaData = {
                            tables: this.tables,
                            relationships: this.relationships
                        };

                        @this.call('saveSchema', schemaData);
                    },

                    exportMigration() {
                        console.log('Export migration clicked. Tables:', this.tables.length);

                        const schemaData = {
                            tables: this.tables,
                            relationships: this.relationships
                        };

                        console.log('Schema data for export:', schemaData);
                        @this.call('exportMigration', schemaData);
                    }
                }
            }
        </script>
    @endpush
</x-filament-panels::page>