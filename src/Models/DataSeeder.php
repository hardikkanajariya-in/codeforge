<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * DataSeeder
 *
 * Eloquent model for managing database seeders with execution tracking,
 * configuration management, and automated seeding capabilities.
 *
 * Key Features:
 * - Comprehensive seeder configuration and metadata storage
 * - Execution history tracking with detailed logging
 * - Priority-based seeder ordering and dependency management
 * - Auto-run capability for automated database seeding
 * - Status tracking for seeder health and execution state
 * - Integration with SeederExecutionLog for audit trails
 *
 * Database Fields:
 * - name: Seeder identifier and class reference
 * - description: Seeder purpose and functionality description
 * - class_name: PHP class name for seeder implementation
 * - file_path: Physical file location of seeder class
 * - configuration: JSON seeder configuration and parameters
 * - status: Current seeder status (active, disabled, error)
 * - type: Seeder type (data, structure, test, production)
 * - priority: Execution order priority for dependency management
 * - auto_run: Automatic execution flag for deployment pipelines
 *
 * Relationships:
 * - executionLogs: HasMany relationship with SeederExecutionLog
 * - Latest execution tracking for status monitoring
 * - Success rate analytics and failure investigation
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class DataSeeder extends Model
{
    protected $fillable = [
        'name',
        'description',
        'class_name',
        'file_path',
        'configuration',
        'status',
        'type',
        'priority',
        'auto_run',
    ];

    protected $casts = [
        'configuration' => 'array',
        'auto_run' => 'boolean',
    ];

    public function executionLogs(): HasMany
    {
        return $this->hasMany(SeederExecutionLog::class, 'seeder_name', 'name');
    }

    public function latestExecution()
    {
        return $this->executionLogs()->latest('started_at')->first();
    }

    public function successfulExecutions(): HasMany
    {
        return $this->executionLogs()->where('status', 'completed');
    }

    public function failedExecutions(): HasMany
    {
        return $this->executionLogs()->where('status', 'failed');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'inactive' => 'gray',
            'draft' => 'warning',
            default => 'gray',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'laravel' => 'heroicon-o-code-bracket',
            'generated' => 'heroicon-o-cpu-chip',
            'custom' => 'heroicon-o-wrench-screwdriver',
            default => 'heroicon-o-document',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAutoRun($query)
    {
        return $query->where('auto_run', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    public function exists(): bool
    {
        return file_exists($this->file_path);
    }

    public function canExecute(): bool
    {
        return $this->status === 'active' && $this->exists() && class_exists($this->class_name);
    }
}
