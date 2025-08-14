<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SeederExecutionLog
 * 
 * Eloquent model for tracking detailed seeder execution logs with
 * comprehensive metrics, status monitoring, and audit trail capabilities.
 * 
 * Key Features:
 * - Detailed seeder execution tracking with timing and record counts
 * - Comprehensive status monitoring (running, completed, failed)
 * - Record-level statistics for created, updated, and failed operations
 * - Error logging with detailed failure analysis
 * - Output capture for debugging and audit purposes
 * - User attribution and execution context tracking
 * - Integration with DataSeeder model for relationship management
 * 
 * Database Fields:
 * - seeder_name: Seeder identifier for tracking and reference
 * - seeder_class: PHP class name for technical identification
 * - status: Execution status (running, completed, failed, cancelled)
 * - records_created: Number of new records inserted
 * - records_updated: Number of existing records modified
 * - records_failed: Number of records that failed processing
 * - execution_time: Total execution duration in seconds
 * - output: Captured seeder output for debugging
 * - error_message: Detailed error information for failures
 * - metadata: Additional execution context and configuration
 * - executed_by: User or system identifier for attribution
 * - started_at: Execution start timestamp
 * - completed_at: Execution completion timestamp
 * 
 * Relationships:
 * - seeder(): BelongsTo relationship with DataSeeder model
 * - Execution history tracking for performance analysis
 * - Status reporting for seeder health monitoring
 * 
 * @package HkDevs\CodeForgeStudio\Models
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SeederExecutionLog extends Model
{
    protected $fillable = [
        'seeder_name',
        'seeder_class',
        'status',
        'records_created',
        'records_updated',
        'records_failed',
        'execution_time',
        'output',
        'error_message',
        'metadata',
        'executed_by',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'execution_time' => 'decimal:3',
    ];

    public function seeder(): BelongsTo
    {
        return $this->belongsTo(DataSeeder::class, 'seeder_name', 'name');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'success',
            'failed' => 'danger',
            'started' => 'warning',
            default => 'gray',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'heroicon-o-check-circle',
            'failed' => 'heroicon-o-x-circle',
            'started' => 'heroicon-o-clock',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    public function getDurationAttribute(): string
    {
        if ($this->execution_time === null) {
            return 'N/A';
        }

        if ($this->execution_time < 1) {
            return round($this->execution_time * 1000) . 'ms';
        }

        return round($this->execution_time, 2) . 's';
    }

    public function getTotalRecordsAttribute(): int
    {
        return $this->records_created + $this->records_updated;
    }

    public function getSuccessRateAttribute(): float
    {
        $total = $this->total_records + $this->records_failed;
        return $total > 0 ? ($this->total_records / $total) * 100 : 0;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('started_at', '>=', now()->subDays($days));
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRunning(): bool
    {
        return $this->status === 'started';
    }
}
