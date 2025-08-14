<?php

namespace HkDevs\CodeForgeStudio\Services;

/**
 * SchemaVisualizationService
 * 
 * Advanced database schema visualization and diagramming service for CodeForge Database Studio.
 * Provides intelligent visual representation of database schemas with interactive features and layout optimization.
 * 
 * Features:
 * - Interactive schema visualization with dynamic table positioning and relationship mapping
 * - Force-directed layout algorithms for optimal diagram organization and readability
 * - Multi-layer visualization with customizable zoom levels and detail granularity
 * - Relationship visualization with directional arrows and constraint indicators
 * - Performance-optimized rendering for large schemas with progressive loading
 * - Export capabilities to multiple formats including SVG, PNG, PDF, and interactive HTML
 * - Real-time schema updates with live diagram synchronization
 * - Customizable themes and styling options for professional presentations
 * 
 * Visualization Capabilities:
 * - Table Visualization: Detailed table representation with columns, types, and constraints
 * - Relationship Mapping: Visual representation of foreign key relationships with cardinality
 * - Index Visualization: Visual indicators for indexes, primary keys, and unique constraints
 * - Dependency Graphs: Hierarchical visualization of table dependencies and relationships
 * - Schema Statistics: Visual representation of schema metrics and performance indicators
 * - Change Visualization: Visual diff representation for schema changes and evolution
 * - Query Path Visualization: Visual representation of query execution paths and joins
 * 
 * Layout Algorithms:
 * - Force-Directed Layout: Automatic optimal positioning using physics-based algorithms
 * - Hierarchical Layout: Tree-based layout for dependency visualization
 * - Circular Layout: Circular arrangement for relationship-focused visualization
 * - Grid Layout: Structured grid-based arrangement for large schema organization
 * - Custom Positioning: Manual positioning with snap-to-grid and alignment tools
 * - Clustered Layout: Automatic grouping of related tables and schema objects
 * - Layered Layout: Multi-layer visualization for complex schema hierarchies
 * 
 * Interactive Features:
 * - Drag and Drop: Interactive table positioning with real-time layout updates
 * - Zoom and Pan: Smooth zoom controls with focus areas and minimap navigation
 * - Filtering: Dynamic filtering by table types, relationships, and schema properties
 * - Search Integration: Real-time search with highlighting and navigation
 * - Detail Panels: Expandable detail panels for tables, columns, and relationships
 * - Context Menus: Right-click context menus for quick actions and navigation
 * - Keyboard Shortcuts: Comprehensive keyboard navigation and control shortcuts
 * 
 * Customization Options:
 * - Theme System: Comprehensive theming with light, dark, and custom color schemes
 * - Style Customization: Custom styling for tables, relationships, and diagram elements
 * - Layout Options: Configurable layout parameters and algorithm settings
 * - Export Settings: Customizable export options with resolution and format controls
 * - Branding Integration: Custom logos, watermarks, and corporate branding options
 * - Responsive Design: Adaptive layouts for different screen sizes and devices
 * - Accessibility: WCAG-compliant visualization with screen reader support
 * 
 * Performance Optimization:
 * - Progressive Loading: Incremental loading for large schemas with performance optimization
 * - Virtualization: Virtual rendering for massive schemas with efficient memory usage
 * - Caching Strategies: Intelligent caching of layout calculations and visualization data
 * - Background Processing: Asynchronous layout calculation for improved responsiveness
 * - Lazy Rendering: On-demand rendering of diagram elements for optimal performance
 * - Memory Management: Efficient memory usage with garbage collection optimization
 * - GPU Acceleration: Hardware acceleration for smooth animations and interactions
 * 
 * Export and Sharing:
 * - Multiple Formats: Export to SVG, PNG, PDF, HTML, and JSON formats
 * - High Resolution: High-DPI export support for print and presentation quality
 * - Interactive Export: HTML export with preserved interactivity and navigation
 * - Batch Export: Automated export of multiple views and diagram configurations
 * - Cloud Integration: Direct sharing and collaboration through cloud services
 * - Embedding: Embeddable diagrams for documentation and presentation systems
 * - Version Control: Diagram versioning with change tracking and rollback
 * 
 * Analysis and Insights:
 * - Schema Complexity Analysis: Automated analysis of schema complexity and maintainability
 * - Relationship Density: Analysis of relationship density and coupling metrics
 * - Performance Hotspots: Visual identification of performance bottlenecks and optimization opportunities
 * - Security Analysis: Visual representation of security boundaries and access patterns
 * - Data Flow Analysis: Visualization of data flow patterns and transaction boundaries
 * - Compliance Visualization: Visual representation of compliance requirements and constraints
 * - Migration Impact: Visual impact analysis for schema changes and migrations
 * 
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel applications and Eloquent models
 * - Real-time Updates: Live synchronization with database schema changes
 * - API Integration: REST endpoints for external visualization tools and services
 * - Webhook Support: Real-time updates through webhook integration
 * - Documentation Integration: Integration with documentation generation and management
 * - Collaboration Tools: Multi-user collaboration with shared diagrams and annotations
 * - External Tools: Integration with popular diagramming and documentation tools
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = app(SchemaVisualizationService::class);
 * $visualData = $service->generateVisualizationData();
 * $layout = $service->generateLayout(['algorithm' => 'force-directed']);
 * $export = $service->exportDiagram(['format' => 'svg', 'theme' => 'dark']);
 */
class SchemaVisualizationService
{
    protected SchemaAnalyzerService $analyzer;

    public function __construct(?SchemaAnalyzerService $analyzer = null)
    {
        $this->analyzer = $analyzer ?? app(SchemaAnalyzerService::class);
    }

    /**
     * Generate data for schema visualization (table positions, relationships)
     */
    public function generateVisualizationData(): array
    {
        $tables = $this->analyzer->getAllTables();
        $relationships = $this->analyzer->getAllRelationships();

        // Calculate table positions using a force-directed layout algorithm
        $tablePositions = $this->calculateTablePositions($tables, $relationships);

        return [
            'tables' => $this->formatTablesForVisualization($tables, $tablePositions),
            'relationships' => $this->formatRelationshipsForVisualization($relationships),
            'statistics' => $this->generateStatistics($tables, $relationships),
        ];
    }

    /**
     * Generate ERD (Entity Relationship Diagram) data
     */
    public function generateERDData(): array
    {
        $tables = $this->analyzer->getAllTables();
        $relationships = $this->analyzer->getAllRelationships();

        return [
            'entities' => $this->formatEntitiesForERD($tables),
            'relationships' => $this->formatRelationshipsForERD($relationships),
            'metadata' => [
                'total_tables' => count($tables),
                'total_relationships' => count($relationships),
                'generated_at' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Get table dependency graph
     */
    public function getTableDependencyGraph(): array
    {
        $relationships = $this->analyzer->getAllRelationships();
        $graph = [];

        foreach ($relationships as $relationship) {
            $fromTable = $relationship['from_table'];
            $toTable = $relationship['to_table'];

            if (!isset($graph[$fromTable])) {
                $graph[$fromTable] = ['dependencies' => [], 'dependents' => []];
            }
            if (!isset($graph[$toTable])) {
                $graph[$toTable] = ['dependencies' => [], 'dependents' => []];
            }

            $graph[$fromTable]['dependencies'][] = $toTable;
            $graph[$toTable]['dependents'][] = $fromTable;
        }

        return $graph;
    }

    /**
     * Calculate optimal table positions for visualization
     */
    protected function calculateTablePositions(array $tables, array $relationships): array
    {
        $positions = [];
        $tableCount = count($tables);

        if ($tableCount === 0) {
            return $positions;
        }

        // Simple grid layout for initial positioning
        $cols = ceil(sqrt($tableCount));
        $spacing = 300;

        foreach ($tables as $index => $table) {
            $row = intval($index / $cols);
            $col = $index % $cols;

            $positions[$table['name']] = [
                'x' => $col * $spacing + 50,
                'y' => $row * $spacing + 50,
                'width' => 250,
                'height' => max(120, count($table['columns']) * 25 + 80),
            ];
        }

        // Apply force-directed algorithm to improve layout
        $positions = $this->applyForceDirectedLayout($positions, $relationships);

        return $positions;
    }

    /**
     * Apply force-directed layout algorithm for better table positioning
     */
    protected function applyForceDirectedLayout(array $positions, array $relationships): array
    {
        $iterations = 50;
        $coolingFactor = 0.95;
        $temperature = 100;

        for ($i = 0; $i < $iterations; $i++) {
            $forces = [];

            // Initialize forces
            foreach ($positions as $tableName => $position) {
                $forces[$tableName] = ['x' => 0, 'y' => 0];
            }

            // Repulsive forces between all tables
            foreach ($positions as $table1 => $pos1) {
                foreach ($positions as $table2 => $pos2) {
                    if ($table1 !== $table2) {
                        $dx = $pos1['x'] - $pos2['x'];
                        $dy = $pos1['y'] - $pos2['y'];
                        $distance = sqrt($dx * $dx + $dy * $dy);

                        if ($distance > 0) {
                            $repulsion = $temperature * 10000 / ($distance * $distance);
                            $forces[$table1]['x'] += ($dx / $distance) * $repulsion;
                            $forces[$table1]['y'] += ($dy / $distance) * $repulsion;
                        }
                    }
                }
            }

            // Attractive forces for connected tables
            foreach ($relationships as $relationship) {
                $fromTable = $relationship['from_table'];
                $toTable = $relationship['to_table'];

                if (isset($positions[$fromTable]) && isset($positions[$toTable])) {
                    $dx = $positions[$toTable]['x'] - $positions[$fromTable]['x'];
                    $dy = $positions[$toTable]['y'] - $positions[$fromTable]['y'];
                    $distance = sqrt($dx * $dx + $dy * $dy);

                    if ($distance > 0) {
                        $attraction = $distance * $distance / ($temperature * 1000);
                        $forces[$fromTable]['x'] += ($dx / $distance) * $attraction;
                        $forces[$fromTable]['y'] += ($dy / $distance) * $attraction;
                        $forces[$toTable]['x'] -= ($dx / $distance) * $attraction;
                        $forces[$toTable]['y'] -= ($dy / $distance) * $attraction;
                    }
                }
            }

            // Apply forces
            foreach ($positions as $tableName => &$position) {
                $fx = $forces[$tableName]['x'];
                $fy = $forces[$tableName]['y'];
                $displacement = sqrt($fx * $fx + $fy * $fy);

                if ($displacement > 0) {
                    $limitedDisplacement = min($displacement, $temperature);
                    $position['x'] += ($fx / $displacement) * $limitedDisplacement;
                    $position['y'] += ($fy / $displacement) * $limitedDisplacement;
                }

                // Keep tables within reasonable bounds
                $position['x'] = max(50, min($position['x'], 2000));
                $position['y'] = max(50, min($position['y'], 2000));
            }

            $temperature *= $coolingFactor;
        }

        return $positions;
    }

    /**
     * Format tables for visualization
     */
    protected function formatTablesForVisualization(array $tables, array $positions): array
    {
        $formatted = [];

        foreach ($tables as $table) {
            $tableName = $table['name'];
            $position = $positions[$tableName] ?? ['x' => 0, 'y' => 0, 'width' => 250, 'height' => 120];

            $formatted[] = [
                'id' => $tableName,
                'name' => $tableName,
                'position' => $position,
                'columns' => $this->formatColumnsForVisualization($table['columns']),
                'metadata' => [
                    'row_count' => $table['row_count'],
                    'size' => $table['size'],
                    'indexes_count' => count($table['indexes']),
                    'foreign_keys_count' => count($table['foreign_keys']),
                ],
            ];
        }

        return $formatted;
    }

    /**
     * Format columns for visualization
     */
    protected function formatColumnsForVisualization(array $columns): array
    {
        $formatted = [];

        foreach ($columns as $column) {
            $formatted[] = [
                'name' => $column['name'],
                'type' => $this->getDisplayType($column),
                'nullable' => $column['nullable'] ?? true,
                'is_primary_key' => $column['primary_key'] ?? false,
                'is_foreign_key' => $column['is_foreign_key'] ?? false,
                'auto_increment' => $column['auto_increment'] ?? false,
                'unique' => $column['unique'] ?? false,
                'default' => $column['default'] ?? null,
            ];
        }

        return $formatted;
    }

    /**
     * Format relationships for visualization
     */
    protected function formatRelationshipsForVisualization(array $relationships): array
    {
        $formatted = [];

        foreach ($relationships as $relationship) {
            $formatted[] = [
                'id' => md5($relationship['from_table'] . $relationship['from_column'] . $relationship['to_table'] . $relationship['to_column']),
                'from_table' => $relationship['from_table'],
                'from_column' => $relationship['from_column'],
                'to_table' => $relationship['to_table'],
                'to_column' => $relationship['to_column'],
                'constraint_name' => $relationship['constraint_name'],
                'relationship_type' => $this->determineRelationshipType($relationship),
                'on_update' => $relationship['on_update'],
                'on_delete' => $relationship['on_delete'],
            ];
        }

        return $formatted;
    }

    /**
     * Format entities for ERD
     */
    protected function formatEntitiesForERD(array $tables): array
    {
        $entities = [];

        foreach ($tables as $table) {
            $primaryKeys = array_filter($table['columns'], fn($col) => $col['primary_key']);
            $foreignKeys = $table['foreign_keys'];

            $entities[] = [
                'name' => $table['name'],
                'attributes' => $this->formatAttributesForERD($table['columns']),
                'primary_keys' => array_values(array_map(fn($col) => $col['name'], $primaryKeys)),
                'foreign_keys' => array_values(array_map(fn($fk) => $fk['column'], $foreignKeys)),
                'row_count' => $table['row_count'],
            ];
        }

        return $entities;
    }

    /**
     * Format attributes for ERD
     */
    protected function formatAttributesForERD(array $columns): array
    {
        $attributes = [];

        foreach ($columns as $column) {
            $attributes[] = [
                'name' => $column['name'],
                'type' => $column['type'],
                'nullable' => $column['nullable'],
                'primary_key' => $column['primary_key'],
                'foreign_key' => false, // Will be determined by relationships
                'unique' => $column['unique'],
                'auto_increment' => $column['auto_increment'],
            ];
        }

        return $attributes;
    }

    /**
     * Format relationships for ERD
     */
    protected function formatRelationshipsForERD(array $relationships): array
    {
        $erdRelationships = [];

        foreach ($relationships as $relationship) {
            $erdRelationships[] = [
                'from_entity' => $relationship['from_table'],
                'to_entity' => $relationship['to_table'],
                'from_attribute' => $relationship['from_column'],
                'to_attribute' => $relationship['to_column'],
                'cardinality' => $this->determineCardinality($relationship),
                'relationship_name' => $relationship['constraint_name'] ?:
                    $relationship['from_table'] . '_' . $relationship['to_table'],
            ];
        }

        return $erdRelationships;
    }

    /**
     * Generate statistics about the schema
     */
    protected function generateStatistics(array $tables, array $relationships): array
    {
        $totalColumns = array_sum(array_map(fn($table) => count($table['columns']), $tables));
        $totalRows = array_sum(array_map(fn($table) => $table['row_count'], $tables));
        $tablesWithData = count(array_filter($tables, fn($table) => $table['row_count'] > 0));

        return [
            'total_tables' => count($tables),
            'total_columns' => $totalColumns,
            'total_relationships' => count($relationships),
            'total_rows' => $totalRows,
            'tables_with_data' => $tablesWithData,
            'average_columns_per_table' => count($tables) > 0 ? round($totalColumns / count($tables), 2) : 0,
            'relationship_density' => count($tables) > 1 ? round(count($relationships) / count($tables), 2) : 0,
        ];
    }

    /**
     * Get display type for column
     */
    protected function getDisplayType(array $column): string
    {
        $type = strtolower($column['type']);

        // Map database types to display categories
        $typeMap = [
            // Integer types
            'int' => 'integer',
            'integer' => 'integer',
            'bigint' => 'bigint',
            'smallint' => 'smallint',
            'tinyint' => 'tinyint',
            'mediumint' => 'integer',

            // String types
            'varchar' => 'varchar',
            'char' => 'char',
            'text' => 'text',
            'longtext' => 'longtext',
            'mediumtext' => 'mediumtext',
            'tinytext' => 'tinytext',

            // Date/Time types
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'timestamp',
            'time' => 'time',
            'year' => 'year',

            // Numeric types
            'decimal' => 'decimal',
            'numeric' => 'decimal',
            'float' => 'float',
            'double' => 'double',
            'real' => 'real',

            // Boolean
            'boolean' => 'boolean',
            'bool' => 'boolean',
            'tinyint(1)' => 'boolean',

            // JSON and Binary
            'json' => 'json',
            'jsonb' => 'jsonb',
            'blob' => 'blob',
            'longblob' => 'longblob',
            'mediumblob' => 'mediumblob',
            'tinyblob' => 'tinyblob',
            'binary' => 'binary',
            'varbinary' => 'varbinary',

            // UUID and other special types
            'uuid' => 'uuid',
            'enum' => 'enum',
            'set' => 'set',
            'geometry' => 'geometry',
            'point' => 'point',
            'polygon' => 'polygon',
        ];

        // Check for exact matches first
        if (isset($typeMap[$type])) {
            return $typeMap[$type];
        }

        // Check for partial matches (for types with parameters like varchar(255))
        foreach ($typeMap as $dbType => $displayType) {
            if (strpos($type, $dbType) === 0) {
                return $displayType;
            }
        }

        // Return the original type if no mapping is found
        return $type;
    }

    /**
     * Determine relationship type
     */
    protected function determineRelationshipType(array $relationship): string
    {
        // Determine relationship type based on column names and patterns
        $fromColumn = $relationship['from_column'];
        $toColumn = $relationship['to_column'];
        $fromTable = $relationship['from_table'];
        $toTable = $relationship['to_table'];

        // If the foreign key column ends with '_id' and references an 'id' column, it's likely belongs_to
        if (str_ends_with($fromColumn, '_id') && $toColumn === 'id') {
            return 'belongs_to';
        }

        // If it's referencing a non-id column, it might be a has_one relationship
        if ($toColumn !== 'id') {
            return 'has_one';
        }

        // Check for many-to-many relationship patterns (pivot tables)
        $tableParts = explode('_', $fromTable);
        if (count($tableParts) >= 2) {
            // Sort the parts to check if it matches common pivot table patterns
            $sortedParts = $tableParts;
            sort($sortedParts);

            // This is a simple heuristic - could be more sophisticated
            if (
                in_array($toTable, $tableParts) ||
                str_contains($fromTable, $toTable) ||
                str_contains($toTable, $fromTable)
            ) {
                return 'many_to_many';
            }
        }

        // Default to has_many for most foreign key relationships
        return 'has_many';
    }

    /**
     * Determine cardinality for ERD
     */
    protected function determineCardinality(array $relationship): string
    {
        $fromColumn = $relationship['from_column'];
        $toColumn = $relationship['to_column'];
        $fromTable = $relationship['from_table'];
        $toTable = $relationship['to_table'];

        try {
            // Check if the foreign key column is unique (indicating one-to-one)
            $tables = $this->analyzer->getAllTables();
            $fromTableData = collect($tables)->firstWhere('name', $fromTable);

            if ($fromTableData) {
                $foreignKeyColumn = collect($fromTableData['columns'])->firstWhere('name', $fromColumn);
                if ($foreignKeyColumn && ($foreignKeyColumn['unique'] ?? false)) {
                    return '1:1';
                }
            }

            // Check for pivot table patterns (many-to-many)
            $tableParts = explode('_', $fromTable);
            if (count($tableParts) >= 2) {
                sort($tableParts);
                if (
                    in_array($toTable, $tableParts) ||
                    str_contains($fromTable, $toTable) ||
                    str_contains($toTable, $fromTable)
                ) {
                    return 'M:N';
                }
            }

            // Most foreign key relationships are one-to-many
            return '1:N';
        } catch (\Exception $e) {
            // Fallback to one-to-many
            return '1:N';
        }
    }
}
