<div wire:ignore>
    <div id="dependencies-diagram" class="schema-diagram" style="height: 600px; width: 100%;"></div>

    @if($dependencyGraph)
        <div class="hidden" id="dependencies-data">{{ json_encode($dependencyGraph) }}</div>
    @else
        <div class="flex items-center justify-center h-full text-gray-500">
            <div class="text-center">
                <x-heroicon-o-squares-plus class="w-8 h-8 mx-auto mb-2" />
                <p>Dependency graph not available.</p>
                <p class="text-sm">Switch to Dependencies view and wait for data to load.</p>
            </div>
        </div>
    @endif

    <!-- Legend -->
    @if($dependencyGraph)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-2">Legend:</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-green-100 border-2 border-green-600 rounded"></div>
                    <span>Root Tables (no dependencies)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-yellow-100 border-2 border-yellow-600 rounded"></div>
                    <span>Leaf Tables (no dependents)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-100 border-2 border-blue-600 rounded"></div>
                    <span>Intermediate Tables</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-gray-100 border-2 border-gray-600 rounded"></div>
                    <span>Isolated Tables</span>
                </div>
            </div>
        </div>
    @endif
</div>

@if($dependencyGraph)
    @script
    <script>
        function initializeDependenciesView() {
            const container = document.getElementById('dependencies-diagram');
            const dataElement = document.getElementById('dependencies-data');

            if (!container || !dataElement) {
                return;
            }

            const data = JSON.parse(dataElement.textContent);

            if (!data || Object.keys(data).length === 0) {
                container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No dependency data found.</div>';
                return;
            }

            // Prepare nodes (tables with dependency info)
            const nodes = Object.keys(data).map(tableName => {
                const tableData = data[tableName];
                const dependencyCount = tableData.dependencies.length;
                const dependentCount = tableData.dependents.length;

                // Determine node color based on dependency level
                let nodeColor;
                if (dependencyCount === 0 && dependentCount > 0) {
                    // Root tables (no dependencies, but others depend on them)
                    nodeColor = { background: '#dcfce7', border: '#16a34a' };
                } else if (dependencyCount > 0 && dependentCount === 0) {
                    // Leaf tables (have dependencies, but nothing depends on them)
                    nodeColor = { background: '#fef3c7', border: '#f59e0b' };
                } else if (dependencyCount > 0 && dependentCount > 0) {
                    // Intermediate tables
                    nodeColor = { background: '#dbeafe', border: '#2563eb' };
                } else {
                    // Isolated tables
                    nodeColor = { background: '#f3f4f6', border: '#6b7280' };
                }

                return {
                    id: tableName,
                    label: createDependencyLabel(tableName, tableData),
                    shape: 'box',
                    font: { face: 'monospace', size: 12 },
                    color: {
                        ...nodeColor,
                        highlight: {
                            background: '#e5e7eb',
                            border: '#374151'
                        }
                    },
                    borderWidth: 2,
                    borderWidthSelected: 3,
                    widthConstraint: { minimum: 180, maximum: 280 },
                    heightConstraint: { minimum: 80 },
                    margin: 10
                };
            });

            // Prepare edges (dependencies)
            const edges = [];
            Object.keys(data).forEach(tableName => {
                const tableData = data[tableName];
                tableData.dependencies.forEach(dependency => {
                    edges.push({
                        id: `${tableName}_depends_on_${dependency}`,
                        from: tableName,
                        to: dependency,
                        label: 'depends on',
                        arrows: { to: { enabled: true, scaleFactor: 0.8 } },
                        color: {
                            color: '#6366f1',
                            highlight: '#4f46e5',
                            hover: '#4f46e5'
                        },
                        width: 2,
                        font: {
                            size: 10,
                            color: '#6366f1',
                            strokeWidth: 2,
                            strokeColor: '#ffffff'
                        },
                        smooth: { type: 'cubicBezier', forceDirection: 'vertical', roundness: 0.4 }
                    });
                });
            });

            // Create network
            const networkData = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
            const options = {
                layout: {
                    improvedLayout: true,
                    hierarchical: {
                        enabled: true,
                        direction: 'UD', // Up-Down
                        sortMethod: 'directed',
                        levelSeparation: 150,
                        nodeSpacing: 200,
                        treeSpacing: 200
                    }
                },
                physics: {
                    enabled: false
                },
                interaction: {
                    dragNodes: true,
                    dragView: true,
                    zoomView: true,
                    selectConnectedEdges: true
                },
                nodes: {
                    font: { face: 'monospace' },
                    margin: 10,
                    borderWidth: 2
                },
                edges: {
                    font: { align: 'middle' },
                    smooth: true
                }
            };

            const network = new vis.Network(container, networkData, options);
            window.currentVisualization = network;

            // Handle node selection
            network.on('selectNode', function (params) {
                if (params.nodes.length > 0) {
                    const selectedTable = params.nodes[0];
                    $wire.call('selectTable', selectedTable);

                    // Highlight connected nodes
                    const connectedNodes = network.getConnectedNodes(selectedTable);
                    const connectedEdges = network.getConnectedEdges(selectedTable);

                    network.selectNodes([selectedTable, ...connectedNodes]);
                    network.selectEdges(connectedEdges);
                }
            });

            // Handle double-click to focus and show details
            network.on('doubleClick', function (params) {
                if (params.nodes.length > 0) {
                    const selectedTable = params.nodes[0];
                    network.focus(selectedTable, {
                        scale: 1.5,
                        animation: {
                            duration: 1000,
                            easingFunction: 'easeInOutQuad'
                        }
                    });

                    // Show detailed dependency info
                    showDependencyDetails(selectedTable, data[selectedTable]);
                }
            });
        }

        function createDependencyLabel(tableName, tableData) {
            let label = `<b>${tableName}</b>\n`;
            label += `${'═'.repeat(Math.max(tableName.length, 12))}\n`;

            const dependencyCount = tableData.dependencies.length;
            const dependentCount = tableData.dependents.length;

            // Dependency summary
            label += `📤 Depends on: ${dependencyCount}\n`;
            label += `📥 Depended by: ${dependentCount}\n`;

            if (dependencyCount > 0) {
                label += `${'─'.repeat(12)}\n`;
                label += `Dependencies:\n`;
                tableData.dependencies.slice(0, 3).forEach(dep => {
                    label += `  → ${dep}\n`;
                });
                if (dependencyCount > 3) {
                    label += `  ... +${dependencyCount - 3} more\n`;
                }
            }

            if (dependentCount > 0) {
                label += `${'─'.repeat(12)}\n`;
                label += `Dependents:\n`;
                tableData.dependents.slice(0, 3).forEach(dep => {
                    label += `  ← ${dep}\n`;
                });
                if (dependentCount > 3) {
                    label += `  ... +${dependentCount - 3} more\n`;
                }
            }

            return label;
        }

        function showDependencyDetails(tableName, tableData) {
            // Create a detailed view of dependencies
            const details = {
                table: tableName,
                dependencies: tableData.dependencies,
                dependents: tableData.dependents,
                level: calculateDependencyLevel(tableName, JSON.parse(document.getElementById('dependencies-data').textContent))
            };

            console.log('Dependency details for', tableName, details);
        }

        function calculateDependencyLevel(tableName, graph) {
            // Calculate how deep in the dependency chain this table is
            let level = 0;
            let visited = new Set();
            let current = [tableName];

            while (current.length > 0 && !visited.has(current[0])) {
                visited.add(current[0]);
                const dependencies = graph[current[0]]?.dependencies || [];
                if (dependencies.length > 0) {
                    current = dependencies;
                    level++;
                } else {
                    break;
                }
            }

            return level;
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('dependencies-diagram')) {
                initializeDependenciesView();
            }
        });

        // Re-initialize when Livewire updates
        document.addEventListener('livewire:load', function () {
            initializeDependenciesView();
        });
    </script>
    @endscript
@endif
