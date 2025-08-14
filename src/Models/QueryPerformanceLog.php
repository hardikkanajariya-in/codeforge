<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * QueryPerformanceLog
 * 
 * Eloquent model for comprehensive database query performance monitoring
 * and analysis with detailed execution metrics and optimization insights.
 * 
 * Key Features:
 * - Real-time query performance tracking with microsecond precision
 * - Query hash generation for duplicate query detection
 * - Multi-connection support for complex database architectures
 * - Automatic execution time formatting with appropriate units
 * - Query binding capture for security and debugging analysis
 * - User attribution and context tracking
 * - Performance trend analysis and optimization recommendations
 * 
 * Database Fields:
 * - connection: Database connection identifier
 * - query: Complete SQL query with parameter placeholders
 * - query_hash: Hash for duplicate query identification
 * - execution_time: Query execution time in milliseconds
 * - rows_affected: Number of rows modified by the query
 * - bindings: Query parameter values for debugging
 * - type: Query type classification (SELECT, INSERT, UPDATE, DELETE)
 * - status: Execution status (success, error, timeout)
 * - error_message: Detailed error information for failed queries
 * - user_id: User context for query attribution
 * - executed_at: Precise timestamp of query execution
 * 
 * Performance Analysis:
 * - Automatic execution time formatting (μs, ms, seconds)
 * - Slow query detection and alerting
 * - Query pattern analysis for optimization opportunities
 * - Resource usage tracking and reporting
 * 
 * @package HkDevs\CodeForgeStudio\Models
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class QueryPerformanceLog extends Model
{
    use HasFactory;

    protected $table = 'query_performance_logs';

    protected $fillable = [
        'connection',
        'query',
        'query_hash',
        'execution_time',
        'rows_affected',
        'bindings',
        'type',
        'status',
        'error_message',
        'user_id',
        'executed_at',
    ];

    protected $casts = [
        'bindings' => 'array',
        'execution_time' => 'decimal:4',
        'rows_affected' => 'integer', // May be null as Laravel's QueryExecuted doesn't provide this
        'executed_at' => 'datetime',
    ];

    public function getFormattedExecutionTimeAttribute(): string
    {
        if ($this->execution_time < 1) {
            return number_format($this->execution_time * 1000, 2) . ' μs';
        } elseif ($this->execution_time < 1000) {
            return number_format($this->execution_time, 2) . ' ms';
        } else {
            return number_format($this->execution_time / 1000, 2) . ' s';
        }
    }

    public function getQueryTypeColorAttribute(): string
    {
        return match ($this->type) {
            'select' => 'success',
            'insert' => 'info',
            'update' => 'warning',
            'delete' => 'danger',
            default => 'gray',
        };
    }

    public function getPerformanceStatusAttribute(): string
    {
        if ($this->execution_time < 100) {
            return 'fast';
        } elseif ($this->execution_time < 1000) {
            return 'moderate';
        } else {
            return 'slow';
        }
    }

    public function scopeSlowQueries($query, $threshold = 1000)
    {
        return $query->where('execution_time', '>=', $threshold);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('executed_at', '>=', now()->subHours($hours));
    }

    /**
     * Get the complete SQL query with bindings interpolated
     */
    public function getCompleteQueryAttribute(): string
    {
        $sql = $this->attributes['query'] ?? '';

        if (empty($this->bindings)) {
            return $sql;
        }

        foreach ($this->bindings as $binding) {
            // Format the binding value based on type
            if ($binding === null) {
                $value = 'NULL';
            } elseif (is_string($binding)) {
                $value = "'" . str_replace("'", "''", $binding) . "'";
            } elseif (is_bool($binding)) {
                $value = $binding ? '1' : '0';
            } elseif (is_numeric($binding)) {
                $value = (string) $binding;
            } else {
                $value = "'" . str_replace("'", "''", (string) $binding) . "'";
            }

            // Replace the first occurrence of ? with the formatted value
            $pos = strpos($sql, '?');
            if ($pos !== false) {
                $sql = substr_replace($sql, $value, $pos, 1);
            }
        }

        return $sql;
    }

    /**
     * Format query for display with syntax highlighting hints
     */
    public function getFormattedQueryAttribute(): string
    {
        $sql = $this->complete_query;

        // Add some basic formatting hints for common SQL keywords
        $keywords = [
            'SELECT',
            'FROM',
            'WHERE',
            'JOIN',
            'LEFT JOIN',
            'RIGHT JOIN',
            'INNER JOIN',
            'INSERT',
            'UPDATE',
            'DELETE',
            'ORDER BY',
            'GROUP BY',
            'HAVING',
            'LIMIT',
            'AND',
            'OR',
            'NOT',
            'IN',
            'EXISTS',
            'BETWEEN',
            'LIKE',
            'IS NULL',
            'IS NOT NULL'
        ];

        foreach ($keywords as $keyword) {
            $sql = preg_replace('/\b' . preg_quote($keyword, '/') . '\b/i', strtoupper($keyword), $sql);
        }

        return $sql;
    }
}
