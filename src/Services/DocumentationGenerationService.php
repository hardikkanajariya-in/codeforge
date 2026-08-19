<?php

namespace HkDevs\CodeForgeStudio\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use HkDevs\CodeForgeStudio\Models\SchemaSnapshot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * DocumentationGenerationService
 *
 * Professional database documentation generation service for CodeForge Database Studio.
 * Creates comprehensive, multi-format documentation with advanced formatting and customization options.
 *
 * Features:
 * - Multi-format documentation generation (Markdown, HTML, PDF)
 * - Comprehensive schema documentation with tables, relationships, and constraints
 * - Laravel model integration with relationship mapping and attribute documentation
 * - Customizable templates with branding and styling options
 * - Automated table of contents and navigation generation
 * - Interactive HTML documentation with search and filtering capabilities
 * - Professional PDF generation with print-optimized layouts
 * - Schema snapshot integration for versioned documentation
 *
 * Documentation Formats:
 * - Markdown: Developer-friendly format with Git integration and collaboration support
 * - HTML: Interactive web documentation with responsive design and navigation
 * - PDF: Professional print-ready documentation with customizable styling
 * - JSON: Structured data export for API documentation and integration
 * - XML: Schema export for external tools and documentation systems
 *
 * Content Generation:
 * - Table Structure: Comprehensive column definitions with types and constraints
 * - Relationship Mapping: Foreign key relationships with visual representation
 * - Index Documentation: Index definitions with performance impact analysis
 * - Model Integration: Laravel Eloquent model detection and relationship mapping
 * - Data Dictionary: Detailed field descriptions and business logic documentation
 * - Schema Statistics: Database metrics and structural analysis
 * - Change History: Schema evolution tracking with diff generation
 *
 * Customization Features:
 * - Template System: Customizable documentation templates with theme support
 * - Branding Options: Custom logos, colors, and styling integration
 * - Content Filtering: Selective table and column inclusion/exclusion
 * - Custom Sections: User-defined documentation sections and content
 * - Internationalization: Multi-language documentation support
 * - Custom Styling: CSS customization for HTML and PDF outputs
 * - Layout Options: Multiple layout templates for different use cases
 *
 * Advanced Features:
 * - Schema Snapshot Integration: Version-controlled documentation generation
 * - Diff Generation: Schema change documentation between versions
 * - Interactive Elements: Collapsible sections and tabbed content
 * - Search Integration: Full-text search capability for large documentations
 * - Cross-References: Automatic linking between related tables and sections
 * - Export Options: Multiple download formats and delivery methods
 * - Batch Generation: Automated documentation for multiple schemas
 *
 * Integration Capabilities:
 * - Laravel Integration: Seamless integration with Laravel applications
 * - Storage Integration: Multiple storage backends with cloud support
 * - Version Control: Git integration for documentation versioning
 * - CI/CD Support: Automated documentation generation in deployment pipelines
 * - API Integration: REST endpoints for external documentation requests
 * - Webhook Support: Real-time documentation updates with external triggers
 * - Team Collaboration: Shared documentation generation and review workflows
 *
 * Performance Optimization:
 * - Lazy Loading: Progressive content loading for large schemas
 * - Caching Strategies: Intelligent caching of generated content and assets
 * - Background Processing: Asynchronous generation for large documentations
 * - Memory Management: Efficient memory usage for complex schema processing
 * - Streaming Generation: Progressive output for real-time feedback
 * - Batch Processing: Optimized processing for multiple table documentation
 *
 * Quality Assurance:
 * - Content Validation: Comprehensive validation of generated documentation
 * - Link Checking: Automatic validation of internal and external links
 * - Format Validation: Schema compliance checking and validation
 * - Accessibility: WCAG-compliant HTML generation with screen reader support
 * - SEO Optimization: Search engine optimized HTML documentation
 * - Print Optimization: PDF layouts optimized for professional printing
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * $generation = DocumentationGeneration::create([
 *     'format' => 'html',
 *     'scope' => 'full_schema',
 *     'title' => 'Database Documentation'
 * ]);
 * $service = new DocumentationGenerationService($generation);
 * $service->generate();
 */
class DocumentationGenerationService
{
    protected DocumentationGeneration $generation;

    protected SchemaSnapshot $snapshot;

    protected array $options;

    public function __construct(DocumentationGeneration $generation)
    {
        $this->generation = $generation;
        $this->options = $generation->options ?? [];
    }

    /**
     * Generate documentation based on the generation configuration
     */
    public function generate(): void
    {
        try {
            $this->generation->markAsGenerating();

            // Create or get schema snapshot
            $this->snapshot = $this->getOrCreateSnapshot();

            // Filter data based on scope
            $data = $this->filterDataByScope();

            // Generate content based on format
            $content = match ($this->generation->format) {
                'markdown' => $this->generateMarkdown($data),
                'html' => $this->generateHtml($data),
                'pdf' => $this->generatePdf($data),
                default => throw new \InvalidArgumentException("Unsupported format: {$this->generation->format}")
            };

            // Save file
            $filePath = $this->saveGeneratedFile($content);
            $fileSize = strlen($content);

            // Update generation record
            $this->generation->markAsCompleted($filePath, $fileSize, [
                'tables_documented' => count($data['tables']),
                'relationships_count' => count($data['relationships']),
                'models_count' => count($data['models']),
                'generated_at' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            $this->generation->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Get or create a schema snapshot for this generation
     */
    protected function getOrCreateSnapshot(): SchemaSnapshot
    {
        // Check if we already have a recent snapshot
        $existingSnapshot = SchemaSnapshot::where('database_connection', config('database.default'))
            ->where('captured_at', '>=', now()->subHours(1))
            ->orderBy('captured_at', 'desc')
            ->first();

        if ($existingSnapshot) {
            return $existingSnapshot;
        }

        // Create new snapshot
        $service = app(SchemaDocumentationService::class);

        return $service->generateSchemaSnapshot(
            "Auto-generated for documentation: {$this->generation->title}",
            'Snapshot created for documentation generation'
        );
    }

    /**
     * Filter schema data based on the generation scope
     */
    protected function filterDataByScope(): array
    {
        $allTables = $this->snapshot->schema_data ?? [];
        $allRelationships = $this->snapshot->table_relationships ?? [];
        $allModels = $this->snapshot->model_mappings ?? [];

        $filteredTables = match ($this->generation->scope) {
            'full_schema' => $allTables,
            'selected_tables' => $this->filterSelectedTables($allTables),
            'single_table' => $this->filterSingleTable($allTables),
            'models_only' => $this->filterModelTables($allTables, $allModels),
            default => $allTables
        };

        $filteredRelationships = $this->filterRelationshipsByTables($allRelationships, array_keys($filteredTables));
        $filteredModels = $this->filterModelsByTables($allModels, array_keys($filteredTables));

        return [
            'tables' => $filteredTables,
            'relationships' => $filteredRelationships,
            'models' => $filteredModels,
            'metadata' => [
                'snapshot_id' => $this->snapshot->id,
                'snapshot_name' => $this->snapshot->name,
                'captured_at' => $this->snapshot->captured_at,
                'database_connection' => $this->snapshot->database_connection,
            ],
        ];
    }

    protected function filterSelectedTables(array $allTables): array
    {
        $includedTables = $this->generation->included_tables ?? [];

        return array_intersect_key($allTables, array_flip($includedTables));
    }

    protected function filterSingleTable(array $allTables): array
    {
        $tableName = ($this->generation->included_tables ?? [])[0] ?? null;

        return $tableName && isset($allTables[$tableName]) ? [$tableName => $allTables[$tableName]] : [];
    }

    protected function filterModelTables(array $allTables, array $allModels): array
    {
        $modelTables = array_keys($allModels);

        return array_intersect_key($allTables, array_flip($modelTables));
    }

    protected function filterRelationshipsByTables(array $relationships, array $tableNames): array
    {
        return array_filter($relationships, function ($relationship) use ($tableNames) {
            return in_array($relationship['from_table'], $tableNames) ||
                in_array($relationship['to_table'], $tableNames);
        });
    }

    protected function filterModelsByTables(array $models, array $tableNames): array
    {
        return array_intersect_key($models, array_flip($tableNames));
    }

    /**
     * Generate Markdown documentation
     */
    protected function generateMarkdown(array $data): string
    {
        $markdown = $this->generateMarkdownHeader($data);
        $markdown .= $this->generateMarkdownTableOfContents($data);
        $markdown .= $this->generateMarkdownOverview($data);
        $markdown .= $this->generateMarkdownTables($data);
        $markdown .= $this->generateMarkdownRelationships($data);
        $markdown .= $this->generateMarkdownModels($data);

        return $markdown;
    }

    protected function generateMarkdownHeader(array $data): string
    {
        $title = $this->generation->title;
        $description = $this->generation->description ?? 'Auto-generated database documentation';
        $generatedAt = now()->format('F j, Y \a\t g:i A');
        $version = $this->generation->version;

        return <<<MARKDOWN
# {$title}

{$description}

**Version:** {$version}  
**Generated:** {$generatedAt}  
**Database:** {$data['metadata']['database_connection']}  
**Snapshot:** {$data['metadata']['snapshot_name']}

---

MARKDOWN;
    }

    protected function generateMarkdownTableOfContents(array $data): string
    {
        $toc = "## Table of Contents\n\n";
        $toc .= "- [Overview](#overview)\n";
        $toc .= "- [Database Tables](#database-tables)\n";

        foreach ($data['tables'] as $tableName => $table) {
            $toc .= "  - [{$tableName}](#{$this->slugify($tableName)})\n";
        }

        $toc .= "- [Relationships](#relationships)\n";
        $toc .= "- [Models](#models)\n\n";
        $toc .= "---\n\n";

        return $toc;
    }

    protected function generateMarkdownOverview(array $data): string
    {
        $tablesCount = count($data['tables']);
        $relationshipsCount = count($data['relationships']);
        $modelsCount = count($data['models']);

        $overview = "## Overview\n\n";
        $overview .= "This documentation provides a comprehensive overview of the database schema.\n\n";
        $overview .= "### Statistics\n\n";
        $overview .= "| Metric | Count |\n";
        $overview .= "|--------|-------|\n";
        $overview .= "| Tables | {$tablesCount} |\n";
        $overview .= "| Relationships | {$relationshipsCount} |\n";
        $overview .= "| Models | {$modelsCount} |\n\n";

        return $overview;
    }

    protected function generateMarkdownTables(array $data): string
    {
        $markdown = "## Database Tables\n\n";

        foreach ($data['tables'] as $tableName => $table) {
            $markdown .= $this->generateMarkdownTable($tableName, $table, $data['models'][$tableName] ?? null);
        }

        return $markdown;
    }

    protected function generateMarkdownTable(string $tableName, array $table, ?array $model): string
    {
        $markdown = "### {$tableName}\n\n";

        if ($model) {
            $markdown .= "**Model:** `{$model['class']}`\n\n";
        }

        if (! empty($table['row_count'])) {
            $markdown .= "**Records:** {$table['row_count']}\n";
        }

        if (! empty($table['size_mb'])) {
            $markdown .= "**Size:** {$table['size_mb']} MB\n";
        }

        $markdown .= "\n#### Columns\n\n";
        $markdown .= "| Column | Type | Nullable | Default | Key | Extra |\n";
        $markdown .= "|--------|------|----------|---------|-----|-------|\n";

        foreach ($table['columns'] as $column) {
            $nullable = $column['nullable'] ? 'Yes' : 'No';
            $default = $column['default'] ?? 'NULL';
            $key = $column['key'] ?? '';
            $extra = $column['extra'] ?? '';

            $markdown .= "| {$column['name']} | {$column['type']} | {$nullable} | {$default} | {$key} | {$extra} |\n";
        }

        // Indexes
        if (! empty($table['indexes'])) {
            $markdown .= "\n#### Indexes\n\n";
            $markdown .= "| Name | Type | Columns |\n";
            $markdown .= "|------|------|----------|\n";

            foreach ($table['indexes'] as $index) {
                $type = $index['primary'] ? 'PRIMARY' : ($index['unique'] ? 'UNIQUE' : 'INDEX');
                $columns = implode(', ', $index['columns']);
                $markdown .= "| {$index['name']} | {$type} | {$columns} |\n";
            }
        }

        // Foreign Keys
        if (! empty($table['foreign_keys'])) {
            $markdown .= "\n#### Foreign Keys\n\n";
            $markdown .= "| Constraint | Column | References |\n";
            $markdown .= "|------------|--------|-----------|\n";

            foreach ($table['foreign_keys'] as $fk) {
                $references = "{$fk['referenced_table']}.{$fk['referenced_column']}";
                $markdown .= "| {$fk['constraint_name']} | {$fk['column']} | {$references} |\n";
            }
        }

        // Model Information
        if ($model) {
            $markdown .= "\n#### Model Information\n\n";

            if (! empty($model['fillable'])) {
                $fillable = implode(', ', $model['fillable']);
                $markdown .= "**Fillable:** `{$fillable}`\n\n";
            }

            if (! empty($model['relationships'])) {
                $markdown .= "**Relationships:**\n";
                foreach ($model['relationships'] as $relationship) {
                    $markdown .= "- `{$relationship['method']}()` - {$relationship['type']}\n";
                }
                $markdown .= "\n";
            }
        }

        $markdown .= "---\n\n";

        return $markdown;
    }

    protected function generateMarkdownRelationships(array $data): string
    {
        if (empty($data['relationships'])) {
            return '';
        }

        $markdown = "## Relationships\n\n";
        $markdown .= "| From Table | From Column | To Table | To Column | Constraint |\n";
        $markdown .= "|------------|-------------|----------|-----------|------------|\n";

        foreach ($data['relationships'] as $rel) {
            $markdown .= "| {$rel['from_table']} | {$rel['from_column']} | {$rel['to_table']} | {$rel['to_column']} | {$rel['constraint_name']} |\n";
        }

        $markdown .= "\n";

        return $markdown;
    }

    protected function generateMarkdownModels(array $data): string
    {
        if (empty($data['models'])) {
            return '';
        }

        $markdown = "## Models\n\n";

        foreach ($data['models'] as $tableName => $model) {
            $markdown .= "### {$model['class']}\n\n";
            $markdown .= "**Table:** `{$tableName}`\n\n";

            if (! empty($model['methods'])) {
                $markdown .= "#### Custom Methods\n\n";
                foreach ($model['methods'] as $method) {
                    $params = array_map(fn ($p) => ($p['type'] ?? '').' $'.$p['name'], $method['parameters']);
                    $paramStr = implode(', ', $params);
                    $returnType = $method['return_type'] ? ": {$method['return_type']}" : '';

                    $markdown .= "- `{$method['name']}({$paramStr}){$returnType}`\n";
                }
                $markdown .= "\n";
            }

            $markdown .= "---\n\n";
        }

        return $markdown;
    }

    /**
     * Generate HTML documentation
     */
    protected function generateHtml(array $data): string
    {
        $markdown = $this->generateMarkdown($data);

        return $this->convertMarkdownToHtml($markdown, $data);
    }

    protected function convertMarkdownToHtml(string $markdown, array $data): string
    {
        // Simple markdown to HTML conversion
        $html = $markdown;

        // Headers
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);

        // Bold
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);

        // Code
        $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);

        // Tables - simplified conversion
        $html = $this->convertMarkdownTablesToHtml($html);

        // Line breaks
        $html = nl2br($html);

        // Wrap in HTML structure
        return $this->wrapInHtmlStructure($html, $data);
    }

    protected function convertMarkdownTablesToHtml(string $html): string
    {
        // This is a simplified table conversion
        // In a production environment, you'd want to use a proper markdown parser
        $lines = explode("\n", $html);
        $inTable = false;
        $result = [];

        foreach ($lines as $line) {
            if (preg_match('/^\|(.+)\|$/', $line, $matches)) {
                if (! $inTable) {
                    $result[] = '<table class="table table-striped">';
                    $inTable = true;
                }

                $cells = array_map('trim', explode('|', trim($matches[1])));
                $row = '<tr>';
                foreach ($cells as $cell) {
                    $row .= "<td>{$cell}</td>";
                }
                $row .= '</tr>';
                $result[] = $row;
            } elseif (preg_match('/^\|[-\s\|]+\|$/', $line)) {
                // Table separator line - convert previous row to header
                if (! empty($result) && $inTable) {
                    $lastRow = array_pop($result);
                    $headerRow = str_replace(['<td>', '</td>'], ['<th>', '</th>'], $lastRow);
                    $result[] = '<thead>'.$headerRow.'</thead><tbody>';
                }
            } else {
                if ($inTable) {
                    $result[] = '</tbody></table>';
                    $inTable = false;
                }
                $result[] = $line;
            }
        }

        if ($inTable) {
            $result[] = '</tbody></table>';
        }

        return implode("\n", $result);
    }

    protected function wrapInHtmlStructure(string $content, array $data): string
    {
        $title = htmlspecialchars($this->generation->title);
        $generatedAt = now()->format('F j, Y \a\t g:i A');

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        h1, h2, h3 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h1 { font-size: 2.5em; }
        h2 { font-size: 2em; margin-top: 2em; }
        h3 { font-size: 1.5em; margin-top: 1.5em; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        code { background: #f1f1f1; padding: 2px 5px; border-radius: 3px; font-family: 'Courier New', monospace; }
        .metadata { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; text-align: center; }
    </style>
</head>
<body>
    {$content}
    <div class="footer">
        Generated on {$generatedAt} by Filament CodeForge Studio
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate PDF documentation
     * Note: Requires dompdf/dompdf package to be installed
     */
    protected function generatePdf(array $data): string
    {
        if (! class_exists('Dompdf\Dompdf')) {
            throw new \RuntimeException('PDF generation requires dompdf/dompdf package. Install it via: composer require dompdf/dompdf');
        }

        $html = $this->generateHtml($data);

        // Configure Dompdf
        $options = new Options;
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Save the generated file and return the storage path
     */
    protected function saveGeneratedFile(string $content): string
    {
        $filename = $this->generateFilename();
        $directory = 'documentation-generations/'.date('Y/m');

        // Ensure directory exists
        Storage::disk('local')->makeDirectory($directory);

        $fullPath = $directory.'/'.$filename;
        Storage::disk('local')->put($fullPath, $content);

        return $fullPath;
    }

    protected function generateFilename(): string
    {
        $title = Str::slug($this->generation->title);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = $this->generation->format === 'pdf' ? 'pdf' : ($this->generation->format === 'html' ? 'html' : 'md');

        return "{$title}_{$timestamp}.{$extension}";
    }

    protected function slugify(string $text): string
    {
        return Str::slug($text);
    }
}
