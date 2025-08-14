<div wire:ignore>
    <div id="erd-diagram" class="schema-diagram" style="height: 600px; width: 100%;"></div>

    @if($erdData)
        <div class="hidden" id="erd-data">{{ json_encode($erdData) }}</div>
    @else
        <div class="flex items-center justify-center h-full text-gray-500">
            <div class="text-center">
                <x-heroicon-o-rectangle-group class="w-8 h-8 mx-auto mb-2" />
                <p>ERD data not available.</p>
                <p class="text-sm">Switch to ERD view and wait for data to load.</p>
            </div>
        </div>
    @endif
</div>

@if($erdData)
    @script
    <script>
        function initializeERDView() {
            const container = document.getElementById('erd-diagram');
            const dataElement = document.getElementById('erd-data');

            if (!container || !dataElement) {
                return;
            }

            const data = JSON.parse(dataElement.textContent);

            if (!data || !data.entities) {
                container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No entities found for ERD.</div>';
                return;
            }

            // Prepare nodes (entities)
            const nodes = data.entities.map((entity, index) => ({
                id: entity.name,
                label: createEntityLabel(entity),
                shape: 'box',
                font: { face: 'monospace', size: 12 },
                color: {
                    background: '#fef3c7',
                    border: '#f59e0b',
                    highlight: {
                        background: '#fde68a',
                        border: '#d97706'
                    }
                },
                borderWidth: 2,
                borderWidthSelected: 3,
                widthConstraint: { minimum: 200, maximum: 350 },
                heightConstraint: { minimum: 120 },
                margin: 10,
                // Arrange in a circle for better initial layout
                x: Math.cos(2 * Math.PI * index / data.entities.length) * 300,
                y: Math.sin(2 * Math.PI * index / data.entities.length) * 300
            }));

            // Prepare edges (relationships)
            const edges = data.relationships.map(rel => ({
                id: `${rel.from_entity}_${rel.to_entity}_${rel.from_attribute}`,
                from: rel.from_entity,
                to: rel.to_entity,
                label: `${rel.cardinality}\n${rel.relationship_name}`,
                arrows: { to: { enabled: true, scaleFactor: 1.0 } },
                color: {
                    color: '#059669',
                    highlight: '#047857',
                    hover: '#047857'
                },
                width: 3,
                font: {
                    size: 11,
                    color: '#059669',
                    strokeWidth: 2,
                    strokeColor: '#ffffff',
                    align: 'middle'
                },
                smooth: { type: 'cubicBezier', forceDirection: 'none', roundness: 0.6 }
            }));

            // Create network
            const networkData = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
            const options = {
                layout: {
                    improvedLayout: true,
                    hierarchical: false
                },
                physics: {
                    enabled: true,
                    stabilization: { iterations: 100 },
                    barnesHut: {
                        gravitationalConstant: -2000,
                        centralGravity: 0.1,
                        springLength: 200,
                        springConstant: 0.04,
                        damping: 0.09
                    }
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

            // Stop physics after stabilization
            network.on('stabilizationIterationsDone', function () {
                network.setOptions({ physics: false });
            });

            // Handle node selection
            network.on('selectNode', function (params) {
                if (params.nodes.length > 0) {
                    const selectedEntity = params.nodes[0];
                    $wire.call('selectTable', selectedEntity);
                }
            });

            // Handle double-click to focus on entity
            network.on('doubleClick', function (params) {
                if (params.nodes.length > 0) {
                    const selectedEntity = params.nodes[0];
                    network.focus(selectedEntity, {
                        scale: 1.8,
                        animation: {
                            duration: 1000,
                            easingFunction: 'easeInOutQuad'
                        }
                    });
                }
            });
        }

        function createEntityLabel(entity) {
            let label = `<b>${entity.name.toUpperCase()}</b>\n`;
            label += `${'═'.repeat(Math.max(entity.name.length, 16))}\n`;

            // Primary keys section
            if (entity.primary_keys && entity.primary_keys.length > 0) {
                label += `🔑 PRIMARY KEYS:\n`;
                entity.primary_keys.forEach(pk => {
                    label += `   ${pk}\n`;
                });
                label += `${'─'.repeat(16)}\n`;
            }

            // Attributes section (limit to prevent overcrowding)
            const displayAttributes = entity.attributes.slice(0, 10);
            label += `📋 ATTRIBUTES:\n`;

            displayAttributes.forEach(attr => {
                let attrLabel = `   ${attr.name}`;

                // Add type info
                attrLabel += ` : ${attr.type}`;

                // Add constraints
                const constraints = [];
                if (!attr.nullable) constraints.push('NOT NULL');
                if (attr.unique) constraints.push('UNIQUE');
                if (attr.auto_increment) constraints.push('AUTO_INC');

                if (constraints.length > 0) {
                    attrLabel += ` [${constraints.join(', ')}]`;
                }

                label += `${attrLabel}\n`;
            });

            if (entity.attributes.length > 10) {
                label += `   ... and ${entity.attributes.length - 10} more\n`;
            }

            // Foreign keys section
            if (entity.foreign_keys && entity.foreign_keys.length > 0) {
                label += `${'─'.repeat(16)}\n`;
                label += `🔗 FOREIGN KEYS:\n`;
                entity.foreign_keys.forEach(fk => {
                    label += `   ${fk}\n`;
                });
            }

            label += `${'═'.repeat(Math.max(entity.name.length, 16))}\n`;
            label += `📊 ${entity.row_count} records`;

            return label;
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('erd-diagram')) {
                initializeERDView();
            }
        });

        // Re-initialize when Livewire updates
        document.addEventListener('livewire:load', function () {
            initializeERDView();
        });
    </script>
    @endscript
@endif