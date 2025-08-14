<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * DatabaseHealthMetric
 * 
 * Eloquent model for storing and managing database health monitoring metrics
 * with comprehensive performance tracking and status management.
 * 
 * Key Features:
 * - Multi-connection database health monitoring
 * - Flexible metric types (response_time, memory_usage, connections, etc.)
 * - Automatic value formatting with appropriate units
 * - Status tracking (healthy, warning, critical)
 * - Metadata storage for additional metric context
 * - Time-series data support with recorded timestamps
 * 
 * Database Fields:
 * - connection: Database connection identifier
 * - metric_type: Type of metric (response_time, memory, etc.)
 * - metric_name: Specific metric identifier
 * - value: Numeric metric value with decimal precision
 * - unit: Measurement unit (ms, MB, count, etc.)
 * - status: Health status (healthy, warning, critical)
 * - metadata: Additional metric context and configuration
 * - recorded_at: Timestamp of metric collection
 * 
 * Value Formatting:
 * - Automatic unit conversion (ms to μs for sub-millisecond times)
 * - Memory size formatting (bytes to KB/MB/GB)
 * - Percentage formatting for ratio-based metrics
 * - Custom formatting based on metric type
 * 
 * @package HkDevs\CodeForgeStudio\Models
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DatabaseHealthMetric extends Model
{
    use HasFactory;

    protected $table = 'database_health_metrics';

    protected $fillable = [
        'connection',
        'metric_type',
        'metric_name',
        'value',
        'unit',
        'status',
        'metadata',
        'recorded_at',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'metadata' => 'array',
        'recorded_at' => 'datetime',
    ];

    public function getFormattedValueAttribute(): string
    {
        $value = $this->value;
        $unit = $this->unit ?? '';

        if ($this->metric_type === 'response_time') {
            if ($value < 1) {
                return number_format($value * 1000, 2) . ' μs';
            } else {
                return number_format($value, 2) . ' ms';
            }
        }

        if ($unit === 'MB') {
            if ($value >= 1024) {
                return number_format($value / 1024, 2) . ' GB';
            }
            return number_format($value, 2) . ' MB';
        }

        if ($unit === '%') {
            return number_format($value, 1) . '%';
        }

        return number_format($value, 2) . ($unit ? ' ' . $unit : '');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'normal' => 'success',
            'warning' => 'warning',
            'critical' => 'danger',
            default => 'gray',
        };
    }

    public function scopeByType($query, $type)
    {
        return $query->where('metric_type', $type);
    }

    public function scopeByConnection($query, $connection)
    {
        return $query->where('connection', $connection);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('recorded_at', '>=', now()->subHours($hours));
    }

    public function scopeWarnings($query)
    {
        return $query->whereIn('status', ['warning', 'critical']);
    }

    /**
     * Get a detailed description of the metric
     */
    public function getMetricDescriptionAttribute(): string
    {
        return match ($this->metric_type) {
            'connection_status' => 'Database connectivity and response time measurement',
            'query_performance' => 'Query execution performance and optimization metrics',
            'database_info' => 'Database size, connection count, and structural information',
            'response_time' => 'Database server response time for health checks',
            default => 'Custom health metric for database monitoring',
        };
    }

    /**
     * Get performance recommendations based on the metric
     */
    public function getRecommendationsAttribute(): array
    {
        $recommendations = [];

        if ($this->status === 'critical') {
            $recommendations[] = '🚨 Immediate attention required';

            if ($this->metric_type === 'response_time' && $this->value > 5000) {
                $recommendations[] = 'Database response time is extremely high - check server resources';
                $recommendations[] = 'Consider optimizing slow queries or scaling database server';
            }

            if ($this->metric_type === 'connection_status') {
                $recommendations[] = 'Database connection failure - verify server availability';
                $recommendations[] = 'Check network connectivity and firewall settings';
            }
        }

        if ($this->status === 'warning') {
            $recommendations[] = '⚠️ Monitor closely for performance degradation';

            if ($this->metric_type === 'response_time' && $this->value > 1000) {
                $recommendations[] = 'Response time elevated - consider query optimization';
            }
        }

        if ($this->status === 'normal') {
            $recommendations[] = '✅ Operating within normal parameters';
        }

        return $recommendations;
    }

    /**
     * Determine if this metric indicates a performance issue
     */
    public function isPerformanceIssue(): bool
    {
        return in_array($this->status, ['warning', 'critical']) &&
            in_array($this->metric_type, ['response_time', 'query_performance']);
    }

    /**
     * Get the threshold information for this metric type
     */
    public function getThresholdInfoAttribute(): ?string
    {
        return match ($this->metric_type) {
            'response_time' => 'Normal: <100ms, Warning: 100-1000ms, Critical: >1000ms',
            'connection_status' => 'Normal: Connected, Critical: Failed',
            'query_performance' => 'Based on query execution time thresholds',
            default => null,
        };
    }
}
