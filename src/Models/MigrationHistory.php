<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MigrationHistory
 * 
 * Eloquent model for tracking detailed migration execution history
 * with comprehensive audit trails and performance monitoring.
 * 
 * Key Features:
 * - Complete migration execution audit trail
 * - Performance tracking with execution timing
 * - User attribution for migration operations
 * - Action-specific tracking (migrate, rollback, reset)
 * - Error logging and failure analysis
 * - Query scopes for efficient data filtering
 * 
 * Database Fields:
 * - migration: Migration file name and identifier
 * - batch: Migration batch number for grouping
 * - action: Operation type (migrate, rollback, reset)
 * - executed_by: User or system identifier for attribution
 * - execution_time: Migration execution duration in seconds
 * - status: Execution status (success, failed, partial)
 * - error_message: Detailed error information for failures
 * - executed_at: Timestamp of migration execution
 * 
 * Query Scopes:
 * - recent(): Latest migration operations
 * - byAction(): Filter by operation type
 * - successful(): Successful executions only
 * - failed(): Failed executions for troubleshooting
 * 
 * @package HkDevs\CodeForgeStudio\Models
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class MigrationHistory extends Model
{
    protected $fillable = [
        'migration',
        'batch',
        'action',
        'executed_by',
        'execution_time',
        'status',
        'error_message',
        'executed_at',
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'execution_time' => 'float',
    ];

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('executed_at', 'desc')->limit($limit);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
