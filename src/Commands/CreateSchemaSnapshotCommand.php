<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;
use Illuminate\Console\Command;

/**
 * CreateSchemaSnapshotCommand
 *
 * Advanced database schema snapshot and versioning utility for CodeForge Database Studio.
 * Creates comprehensive snapshots of database structure for version control and change tracking.
 *
 * Features:
 * - Complete database schema capture including tables, columns, indexes, and relationships
 * - Custom naming and description support for organized snapshot management
 * - Multi-connection support for complex database architectures
 * - Baseline snapshot marking for migration reference points
 * - Automatic metadata collection (table counts, relationship mapping, model detection)
 * - Hash-based change detection for efficient schema comparison
 * - Integration with Laravel models and Eloquent relationships
 *
 * Snapshot Contents:
 * - Table Structures: Column definitions, data types, constraints
 * - Indexes and Keys: Primary keys, foreign keys, unique constraints, indexes
 * - Relationships: Foreign key relationships and model associations
 * - Model Mapping: Laravel Eloquent model detection and association
 * - Metadata: Creation timestamps, schema statistics, change hashes
 *
 * Use Cases:
 * - Schema version control and change tracking
 * - Pre-migration baseline creation
 * - Database structure documentation
 * - Schema comparison and diff generation
 * - Rollback point creation for safe migrations
 * - Multi-environment schema synchronization
 *
 * Baseline Features:
 * - Mark snapshots as reference points for future comparisons
 * - Establish migration starting points
 * - Create stable schema references for team development
 * - Support for multiple baseline snapshots per project
 *
 * Integration Support:
 * - Compatible with CodeForge documentation generation
 * - Supports automated snapshot scheduling
 * - Integrates with migration tracking system
 * - Exportable snapshot data for external tools
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Create a basic schema snapshot
 * php artisan codeforge:create-snapshot
 *
 * # Create named snapshot with description
 * php artisan codeforge:create-snapshot --name="Pre-Migration Baseline" --description="Schema before user system refactor"
 *
 * # Create baseline snapshot for specific connection
 * php artisan codeforge:create-snapshot --connection=mysql --baseline
 *
 * # Create deployment snapshot
 * php artisan codeforge:create-snapshot --name="Production Deploy v2.1" --baseline
 */
class CreateSchemaSnapshotCommand extends Command
{
    protected $signature = 'codeforge:create-snapshot 
                           {--name= : Custom name for the snapshot}
                           {--description= : Custom description}
                           {--connection= : Database connection to use}
                           {--baseline : Mark this snapshot as baseline}';

    protected $description = 'Create a schema snapshot of the current database';

    public function handle(): int
    {
        $this->info('Creating database schema snapshot...');

        try {
            $connection = $this->option('connection') ?: config('database.default');
            $name = $this->option('name') ?: 'CLI Snapshot - '.now()->format('Y-m-d H:i:s');
            $description = $this->option('description') ?: 'Schema snapshot created via CLI';

            $this->line("Connection: {$connection}");
            $this->line("Name: {$name}");

            // Create the snapshot
            $service = app(SchemaDocumentationService::class, ['connection' => $connection]);
            $snapshot = $service->generateSchemaSnapshot($name, $description);

            if ($this->option('baseline')) {
                $snapshot->markAsBaseline();
                $this->info('Marked as baseline snapshot');
            }

            $this->info('✅ Schema snapshot created successfully!');
            $this->line("ID: {$snapshot->id}");
            $this->line("Tables: {$snapshot->tables_count}");
            $this->line("Relationships: {$snapshot->relationships_count}");
            $this->line("Models: {$snapshot->models_count}");
            $this->line("Hash: {$snapshot->hash}");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Snapshot creation failed: '.$e->getMessage());

            if ($this->option('verbose')) {
                $this->line($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }
}
