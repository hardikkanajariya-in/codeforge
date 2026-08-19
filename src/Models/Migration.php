<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Migration
 *
 * Eloquent model for Laravel migration management with enhanced functionality
 * for CodeForge Database Studio migration tracking and analysis.
 *
 * Key Features:
 * - Laravel migrations table integration with batch tracking
 * - File system integration for migration discovery
 * - Migration status analysis and dependency tracking
 * - Batch grouping for rollback operations
 * - Enhanced migration metadata and execution tracking
 *
 * Database Fields:
 * - migration: Migration file name identifier
 * - batch: Batch number for grouping related migrations
 *
 * Enhanced Functionality:
 * - File system scanning for migration discovery
 * - Migration status comparison (pending vs executed)
 * - Dependency analysis for safe rollback operations
 * - Integration with CodeForge migration tracking services
 *
 * Static Methods:
 * - getAllMigrations(): Discover all migration files
 * - Migration analysis and comparison utilities
 * - Batch management for grouped operations
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class Migration extends Model
{
    protected $table = 'migrations';

    // Use migration name as primary key since that's what uniquely identifies migrations
    protected $primaryKey = 'migration';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'migration',
        'batch',
        'status',
        'executed_at',
    ];

    protected $casts = [
        'batch' => 'integer',
        'executed_at' => 'datetime',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // No global scopes needed - logic is handled in ListMigrations page
    }

    public static function getAllMigrations(): Collection
    {
        $migrationPath = database_path('migrations');
        $migrationFiles = collect(File::files($migrationPath))
            ->map(function ($file) {
                return pathinfo($file->getFilename(), PATHINFO_FILENAME);
            })
            ->sort();

        $executedMigrations = collect();
        if (Schema::hasTable('migrations')) {
            $executedMigrations = static::withoutGlobalScopes()->get()->keyBy('migration');
        }

        return $migrationFiles->map(function ($migration) use ($executedMigrations) {
            $executed = $executedMigrations->get($migration);

            // Create a proper Migration model instance
            $migrationModel = new static;
            $migrationModel->migration = $migration;
            $migrationModel->batch = $executed->batch ?? null;
            $migrationModel->executed_at = $executed->created_at ?? null;
            $migrationModel->status = $executed ? 'executed' : 'pending';

            // Mark as existing if it's executed, or new if pending
            $migrationModel->exists = (bool) $executed;

            // Set the primary key value
            $migrationModel->setAttribute($migrationModel->getKeyName(), $migration);

            return $migrationModel;
        });
    }
}
