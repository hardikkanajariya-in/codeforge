/**
 * Schema Designer JavaScript Module
 * Enhanced database schema visualization with D3.js
 */

class SchemaDesigner {
    constructor() {
        this.svg = null;
        this.g = null;
        this.simulation = null;
        this.data = null;
        this.nodes = [];
        this.links = [];
        this.width = 0;
        this.height = 0;
        this.zoom = null;
        this.showRelationships = true;
        this.searchTerm = '';
        this.selectedTable = null;

        this.init();
    }

    async init() {
        try {
            await this.loadData();
            this.setupSVG();
            this.createVisualization();
            this.setupEventListeners();
            this.hideLoading();

            // Debug output for relationship visibility
            setTimeout(() => {
                this.debugRelationships();
            }, 2000);
        } catch (error) {
            console.error('Schema designer initialization failed:', error);
            this.showError('Failed to initialize schema designer: ' + error.message);
        }
    }

    debugRelationships() {
        console.log('=== RELATIONSHIP DEBUG ===');
        console.log('showRelationships:', this.showRelationships);
        console.log('linkElements:', this.linkElements);
        if (this.linkElements) {
            console.log('linkElements size:', this.linkElements.size());
            console.log('linkElements data:', this.linkElements.data());

            // Check if any paths exist in the DOM
            const paths = this.svg.selectAll('path.relationship-line');
            console.log('DOM paths found:', paths.size());

            // Check visibility styles
            this.linkElements.each(function (d, i) {
                const element = d3.select(this);
                console.log(`Link ${i}:`, {
                    data: d,
                    display: element.style('display'),
                    opacity: element.style('opacity'),
                    stroke: element.attr('stroke'),
                    strokeWidth: element.attr('stroke-width'),
                    d: element.attr('d')
                });
            });
        }
        console.log('=== END RELATIONSHIP DEBUG ===');
    }

    async loadData() {
        try {
            const dataElement = document.getElementById('schema-data');
            if (!dataElement) {
                throw new Error('Schema data element not found');
            }

            this.data = JSON.parse(dataElement.textContent);
            console.log('Loaded schema data:', this.data);

            // Check if we have visualization data
            if (!this.data.visualizationData) {
                console.warn('No visualization data found, creating empty structure');
                this.data.visualizationData = {
                    tables: [],
                    relationships: [],
                    statistics: {
                        total_tables: 0,
                        total_columns: 0,
                        total_relationships: 0,
                        total_rows: 0,
                        tables_with_data: 0,
                        average_columns_per_table: 0,
                        relationship_density: 0,
                    }
                };
            }

            // Ensure tables have proper structure
            if (this.data.visualizationData.tables) {
                this.data.visualizationData.tables = this.data.visualizationData.tables.map(table => ({
                    ...table,
                    columns: table.columns || [],
                    metadata: table.metadata || {},
                    position: table.position || { x: 0, y: 0 }
                }));
            }

        } catch (error) {
            console.error('Error loading schema data:', error);
            this.showError('Failed to load schema data: ' + error.message);

            // Create minimal fallback data
            this.data = {
                visualizationData: {
                    tables: [],
                    relationships: [],
                    statistics: {
                        total_tables: 0,
                        total_columns: 0,
                        total_relationships: 0,
                        total_rows: 0,
                        tables_with_data: 0,
                        average_columns_per_table: 0,
                        relationship_density: 0,
                    }
                }
            };
        }
    }

    setupSVG() {
        const container = d3.select('#schema-canvas');
        const containerNode = container.node();

        this.width = containerNode.clientWidth || 800;
        this.height = containerNode.clientHeight || 600;

        // Clear any existing content
        container.selectAll('*').remove();

        // Create SVG
        this.svg = container
            .append('svg')
            .attr('width', this.width)
            .attr('height', this.height)
            .style('background-color', '#fafafa');

        // Create defs for arrowheads
        const defs = this.svg.append('defs');

        defs.append('marker')
            .attr('id', 'arrowhead')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', 8)
            .attr('refY', 0)
            .attr('markerWidth', 8)
            .attr('markerHeight', 8)
            .attr('orient', 'auto')
            .append('path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr('fill', '#3b82f6')
            .attr('stroke', 'none');

        // Create zoom behavior
        this.zoom = d3.zoom()
            .scaleExtent([0.1, 3])
            .on('zoom', (event) => {
                if (this.g) {
                    this.g.attr('transform', event.transform);
                }
            });

        this.svg.call(this.zoom);

        // Create main group
        this.g = this.svg.append('g');

        console.log('SVG setup complete. Dimensions:', this.width, 'x', this.height);
    }

    createVisualization() {
        if (!this.data || !this.data.visualizationData) {
            this.showError('No visualization data available');
            return;
        }

        const vizData = this.data.visualizationData;

        // Handle empty data gracefully
        if (!vizData.tables || vizData.tables.length === 0) {
            this.showEmptyState();
            return;
        }

        this.prepareData(vizData);
        this.renderLinks();
        this.renderNodes();
        this.createForceSimulation();

        // Add debugging info
        console.log('Visualization created:');
        console.log('- Nodes:', this.nodes.length);
        console.log('- Links:', this.links.length);
        console.log('- Link elements:', this.linkElements ? this.linkElements.size() : 0);

        // Auto-fit to screen after a short delay
        setTimeout(() => {
            this.fitToScreen();
        }, 1000);
    }

    showEmptyState() {
        // Create SVG for empty state instead of HTML divs
        const container = d3.select('#schema-canvas');
        container.selectAll('*').remove();

        // Create SVG
        this.svg = container
            .append('svg')
            .attr('width', this.width || 800)
            .attr('height', this.height || 600)
            .style('background-color', '#fafafa');

        const centerX = (this.width || 800) / 2;
        const centerY = (this.height || 600) / 2;

        // Create empty state group in SVG
        const emptyStateGroup = this.svg.append('g').attr('class', 'empty-state');

        // Icon
        emptyStateGroup
            .append('text')
            .attr('x', centerX)
            .attr('y', centerY - 40)
            .attr('text-anchor', 'middle')
            .attr('font-size', '48px')
            .text('📊');

        // Title
        emptyStateGroup
            .append('text')
            .attr('x', centerX)
            .attr('y', centerY)
            .attr('text-anchor', 'middle')
            .attr('font-size', '18px')
            .attr('font-weight', '600')
            .attr('fill', '#374151')
            .text('No Database Tables Found');

        // Description
        emptyStateGroup
            .append('text')
            .attr('x', centerX)
            .attr('y', centerY + 25)
            .attr('text-anchor', 'middle')
            .attr('font-size', '14px')
            .attr('fill', '#6b7280')
            .text('Create some tables to see the schema visualization');

        this.hideLoading();
    }

    prepareData(vizData) {
        console.log('Preparing data with:', vizData);

        // Calculate better initial positions
        const centerX = this.width / 2;
        const centerY = this.height / 2;
        const numTables = vizData.tables.length;
        const radius = Math.min(this.width, this.height) / 4;

        // Prepare nodes (tables)
        this.nodes = vizData.tables.map((table, index) => {
            // Position tables in a circle initially for better distribution
            const angle = (2 * Math.PI * index) / numTables;
            const x = centerX + radius * Math.cos(angle);
            const y = centerY + radius * Math.sin(angle);

            return {
                id: table.name,
                name: table.name,
                columns: table.columns || [],
                metadata: table.metadata || {},
                x: table.position?.x || x,
                y: table.position?.y || y,
                width: 250,
                height: Math.max(120, (table.columns?.length || 0) * 20 + 60)
            };
        });

        // Prepare links (relationships) - use real data from backend
        this.links = (vizData.relationships || []).map(rel => {
            console.log('Processing relationship:', rel);
            return {
                source: rel.from_table,
                target: rel.to_table,
                type: rel.relationship_type || rel.type || 'foreign_key',
                from_column: rel.from_column,
                to_column: rel.to_column,
                constraint_name: rel.constraint_name
            };
        }).filter(link => {
            // Ensure both source and target tables exist
            const sourceExists = this.nodes.some(node => node.id === link.source);
            const targetExists = this.nodes.some(node => node.id === link.target);
            if (!sourceExists || !targetExists) {
                console.warn('Skipping relationship with missing table:', link);
                return false;
            }
            return true;
        });

        console.log('Prepared nodes:', this.nodes.length, 'links:', this.links.length);
    } createForceSimulation() {
        // Ensure we have links and nodes before creating simulation
        if (!this.nodes || this.nodes.length === 0) {
            console.warn('No nodes available for simulation');
            return;
        }

        console.log('Creating force simulation with:');
        console.log('- Nodes:', this.nodes.map(n => n.id));
        console.log('- Links:', this.links.map(l => `${l.source} -> ${l.target}`));

        this.simulation = d3.forceSimulation(this.nodes)
            .force('link', d3.forceLink(this.links).id(d => d.id).distance(300).strength(0.5))
            .force('charge', d3.forceManyBody().strength(-800))
            .force('center', d3.forceCenter(this.width / 2, this.height / 2))
            .force('collision', d3.forceCollide().radius(d => Math.max(d.width, d.height) / 2 + 30))
            .force('x', d3.forceX(this.width / 2).strength(0.1))
            .force('y', d3.forceY(this.height / 2).strength(0.1))
            .alphaDecay(0.02)
            .on('tick', () => this.ticked());

        console.log('Force simulation created with', this.nodes.length, 'nodes and', this.links.length, 'links');
    }

    renderLinks() {
        if (!this.links || this.links.length === 0) {
            console.log('No relationships to render');
            return;
        }

        console.log('Rendering', this.links.length, 'relationships');

        const linkGroup = this.g.append('g').attr('class', 'links');

        this.linkElements = linkGroup
            .selectAll('.link')
            .data(this.links)
            .join('path')
            .attr('class', 'relationship-line')
            .attr('fill', 'none')
            .attr('stroke', '#3b82f6')
            .attr('stroke-width', 3)
            .attr('stroke-opacity', 0.8)
            .attr('marker-end', 'url(#arrowhead)')
            .style('cursor', 'pointer')
            .on('mouseover', function (event, d) {
                d3.select(this)
                    .attr('stroke', '#1d4ed8')
                    .attr('stroke-width', 4)
                    .attr('stroke-opacity', 1);

                // Show tooltip
                const tooltip = d3.select('body').append('div')
                    .attr('class', 'relationship-tooltip')
                    .style('position', 'absolute')
                    .style('background', 'rgba(0,0,0,0.9)')
                    .style('color', 'white')
                    .style('padding', '8px 12px')
                    .style('border-radius', '6px')
                    .style('font-size', '12px')
                    .style('pointer-events', 'none')
                    .style('opacity', 0)
                    .style('z-index', '1000');

                tooltip.html(`${d.source.id || d.source}.${d.from_column} → ${d.target.id || d.target}.${d.to_column}`)
                    .style('left', (event.pageX + 10) + 'px')
                    .style('top', (event.pageY - 10) + 'px')
                    .transition()
                    .duration(200)
                    .style('opacity', 1);
            })
            .on('mouseout', function (event, d) {
                d3.select(this)
                    .attr('stroke', '#3b82f6')
                    .attr('stroke-width', 3)
                    .attr('stroke-opacity', 0.8);

                d3.selectAll('.relationship-tooltip').remove();
            });

        console.log('Rendered relationship elements:', this.linkElements.size());

        // Force initial visibility
        this.linkElements.style('display', 'block');
    }

    renderNodes() {
        const nodeGroup = this.g.append('g').attr('class', 'nodes');

        const nodeEnter = nodeGroup
            .selectAll('.node')
            .data(this.nodes)
            .join('g')
            .attr('class', 'table-node')
            .call(d3.drag()
                .on('start', (event, d) => this.dragstarted(event, d))
                .on('drag', (event, d) => this.dragged(event, d))
                .on('end', (event, d) => this.dragended(event, d))
            );

        // Create table background
        nodeEnter
            .append('rect')
            .attr('class', 'table-body')
            .attr('rx', 8)
            .attr('ry', 8)
            .attr('fill', 'white')
            .attr('stroke', '#e5e7eb')
            .attr('stroke-width', 2);

        // Create table header
        nodeEnter
            .append('rect')
            .attr('class', 'table-header')
            .attr('rx', 8)
            .attr('ry', 8)
            .attr('fill', '#f9fafb')
            .attr('stroke', '#e5e7eb')
            .attr('stroke-width', 1)
            .attr('height', 35);

        // Add table title
        nodeEnter
            .append('text')
            .attr('class', 'table-title')
            .attr('x', 10)
            .attr('y', 25)
            .text(d => d.name);

        // Add columns
        nodeEnter.each((d, i, nodes) => {
            const node = d3.select(nodes[i]);

            d.columns.forEach((column, index) => {
                const y = 50 + index * 20;

                // Column background (for hover effects)
                node.append('rect')
                    .attr('x', 2)
                    .attr('y', y - 12)
                    .attr('width', d.width - 4)
                    .attr('height', 18)
                    .attr('fill', 'transparent')
                    .attr('class', 'column-bg')
                    .on('mouseover', function () {
                        d3.select(this).attr('fill', '#f3f4f6');
                    })
                    .on('mouseout', function () {
                        d3.select(this).attr('fill', 'transparent');
                    });

                // Key indicator
                if (column.is_primary_key || column.is_foreign_key) {
                    node.append('circle')
                        .attr('cx', 15)
                        .attr('cy', y - 2)
                        .attr('r', 3)
                        .attr('fill', column.is_primary_key ? '#dc2626' : '#2563eb');
                }

                // Column name
                node.append('text')
                    .attr('class', `column-text column-name ${column.is_primary_key ? 'primary-key' : ''} ${column.is_foreign_key ? 'foreign-key' : ''}`)
                    .attr('x', column.is_primary_key || column.is_foreign_key ? 25 : 10)
                    .attr('y', y)
                    .text(column.name);

                // Column type
                node.append('text')
                    .attr('class', 'column-text column-type')
                    .attr('x', d.width - 10)
                    .attr('y', y)
                    .attr('text-anchor', 'end')
                    .text(column.type)
                    .style('font-size', '10px');
            });

            // Update node dimensions
            d.height = Math.max(120, 50 + d.columns.length * 20 + 10);

            // Update rectangles with correct dimensions
            node.select('.table-body')
                .attr('width', d.width)
                .attr('height', d.height);

            node.select('.table-header')
                .attr('width', d.width);
        });

        // Add click handlers
        nodeEnter.on('click', (event, d) => {
            this.selectTable(d);
        });

        this.nodeElements = nodeEnter;
    }

    ticked() {
        if (this.linkElements) {
            this.linkElements.attr('d', d => {
                const source = d.source;
                const target = d.target;

                // Ensure source and target have valid coordinates
                if (!source || !target || !source.x || !target.x) {
                    return '';
                }

                // Calculate connection points on node edges
                const dx = target.x - source.x;
                const dy = target.y - source.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance === 0) {
                    return '';
                }

                // Calculate edge points
                const sourceRadius = Math.sqrt((source.width / 2) * (source.width / 2) + (source.height / 2) * (source.height / 2));
                const targetRadius = Math.sqrt((target.width / 2) * (target.width / 2) + (target.height / 2) * (target.height / 2));

                const sourceX = source.x + (dx / distance) * (sourceRadius * 0.7);
                const sourceY = source.y + (dy / distance) * (sourceRadius * 0.7);
                const targetX = target.x - (dx / distance) * (targetRadius * 0.7);
                const targetY = target.y - (dy / distance) * (targetRadius * 0.7);

                return `M${sourceX},${sourceY}L${targetX},${targetY}`;
            });
        }

        if (this.nodeElements) {
            this.nodeElements.attr('transform', d => `translate(${d.x - d.width / 2},${d.y - d.height / 2})`);
        }
    }

    dragstarted(event, d) {
        if (!event.active) this.simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
    }

    dragged(event, d) {
        d.fx = event.x;
        d.fy = event.y;
    }

    dragended(event, d) {
        if (!event.active) this.simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
    }

    selectTable(table) {
        this.selectedTable = table;
        this.showTableDetails(table);

        // Highlight selected table
        this.nodeElements.classed('selected', d => d.id === table.id);

        // Notify Livewire
        if (typeof $wire !== 'undefined') {
            $wire.call('selectTable', table.name);
        }
    }

    showTableDetails(table) {
        const panel = document.getElementById('details-panel');
        const title = document.getElementById('details-title');
        const content = document.getElementById('details-content');

        title.textContent = table.name;

        let html = `
        <div class="table-info">
            <h4>Table Information</h4>
            <p><strong>Columns:</strong> ${table.columns.length}</p>
            <p><strong>Estimated Rows:</strong> ${table.metadata.row_count || 'N/A'}</p>
        </div>
        
        <div class="columns-list">
            <h4>Columns</h4>
            <div class="columns">
        `;

        table.columns.forEach(column => {
            const badges = [];
            if (column.is_primary_key) badges.push('<span class="badge primary">PK</span>');
            if (column.is_foreign_key) badges.push('<span class="badge foreign">FK</span>');
            if (!column.nullable) badges.push('<span class="badge required">Required</span>');

            html += `
            <div class="column-item">
                <div class="column-name">${column.name}</div>
                <div class="column-info">
                    <span class="column-type">${column.type}</span>
                    ${badges.join(' ')}
                </div>
            </div>
        `;
        });

        html += '</div></div>';

        content.innerHTML = html;
        panel.style.display = 'flex';
    }

    hideLoading() {
        const loader = document.getElementById('loading-indicator');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 300);
        }
    }

    showError(message) {
        const loader = document.getElementById('loading-indicator');
        if (loader) {
            loader.innerHTML = `
            <div style="color: #dc2626; text-align: center;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚠️</div>
                <div style="font-weight: 600; margin-bottom: 0.5rem;">Error</div>
                <div style="font-size: 0.875rem;">${message}</div>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
                    Reload Page
                </button>
            </div>
        `;
            loader.style.display = 'flex';
        }
    }

    setupEventListeners() {
        // Add any additional event listeners here if needed
        // This method is called after initialization
        console.log('Schema designer event listeners set up');
    }

    // Public methods for controls
    search(term) {
        this.searchTerm = term.toLowerCase();

        if (this.nodeElements) {
            this.nodeElements
                .style('opacity', d => {
                    if (!term) return 1;
                    const matchesName = d.name.toLowerCase().includes(this.searchTerm);
                    const matchesColumn = d.columns.some(col =>
                        col.name.toLowerCase().includes(this.searchTerm)
                    );
                    return matchesName || matchesColumn ? 1 : 0.3;
                });
        }
    }

    fitToScreen() {
        if (!this.svg || !this.nodeElements) return;

        const bounds = this.g.node().getBBox();
        const padding = 50;

        const scale = Math.min(
            (this.width - padding * 2) / bounds.width,
            (this.height - padding * 2) / bounds.height,
            1
        );

        const translateX = (this.width - bounds.width * scale) / 2 - bounds.x * scale;
        const translateY = (this.height - bounds.height * scale) / 2 - bounds.y * scale;

        this.svg.transition()
            .duration(750)
            .call(this.zoom.transform, d3.zoomIdentity.translate(translateX, translateY).scale(scale));
    }

    resetView() {
        if (this.svg) {
            this.svg.transition()
                .duration(750)
                .call(this.zoom.transform, d3.zoomIdentity);
        }
    }

    toggleRelationships() {
        this.showRelationships = !this.showRelationships;

        if (this.linkElements) {
            this.linkElements
                .style('display', this.showRelationships ? 'block' : 'none')
                .style('opacity', this.showRelationships ? 1 : 0);
        }

        console.log('Relationships visibility:', this.showRelationships ? 'shown' : 'hidden');
        console.log('Link elements count:', this.linkElements ? this.linkElements.size() : 0);

        // Update control button state
        const btn = document.querySelector('[onclick="toggleRelationships()"]');
        if (btn) {
            btn.style.backgroundColor = this.showRelationships ? '#3b82f6' : '';
            btn.style.color = this.showRelationships ? 'white' : '';
        }
    }

    updateStatus(message) {
        const statusEl = document.getElementById('view-status');
        if (statusEl) {
            statusEl.textContent = message;
        }
        console.log('Status:', message);
    }
}

// Global functions for UI controls
function toggleRelationships() {
    console.log('Global toggleRelationships function called');
    if (window.schemaDesigner) {
        window.schemaDesigner.toggleRelationships();
    } else {
        console.error('window.schemaDesigner not available in global toggleRelationships');
    }
}

function handleSearch(term) {
    console.log('Global handleSearch function called with:', term);
    if (window.schemaDesigner) {
        window.schemaDesigner.search(term);
    } else {
        console.error('window.schemaDesigner not available in global handleSearch');
    }
}

function resetView() {
    console.log('Global resetView function called');
    if (window.schemaDesigner) {
        window.schemaDesigner.resetView();
    } else {
        console.error('window.schemaDesigner not available in global resetView');
    }
}

function fitToScreen() {
    console.log('Global fitToScreen function called');
    if (window.schemaDesigner) {
        window.schemaDesigner.fitToScreen();
    } else {
        console.error('window.schemaDesigner not available in global fitToScreen');
    }
}

function exportDiagram() {
    console.log('Export functionality triggered');
    // This will be handled by Livewire via the wire:click="exportERD" in the blade template
}

function closeDetailsPanel() {
    console.log('Global closeDetailsPanel function called');
    document.getElementById('details-panel').style.display = 'none';
}

// Make functions globally available
window.toggleRelationships = toggleRelationships;
window.handleSearch = handleSearch;
window.resetView = resetView;
window.fitToScreen = fitToScreen;
window.closeDetailsPanel = closeDetailsPanel;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing SchemaDesigner...');
    window.schemaDesigner = new SchemaDesigner();
    console.log('SchemaDesigner initialized and assigned to window.schemaDesigner');
});

// Handle Livewire events
document.addEventListener('livewire:init', function () {
    if (typeof $wire !== 'undefined') {
        $wire.on('view-changed', (view) => {
            console.log('View changed to:', view);
            // Handle view changes if needed
        });
    }
});
