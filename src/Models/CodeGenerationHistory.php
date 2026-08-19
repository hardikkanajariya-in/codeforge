<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CodeGenerationHistory
 *
 * Eloquent model for tracking code generation operations and maintaining
 * a comprehensive history of all generated files and components.
 *
 * Key Features:
 * - Complete generation tracking with metadata and configuration
 * - Parent-child relationship support for grouped generations
 * - Performance metrics with execution timing and file size tracking
 * - Error handling and success status tracking
 * - User attribution and audit trail capabilities
 * - Template usage tracking for generation analytics
 *
 * Database Fields:
 * - generation_id: Unique identifier for generation batch
 * - type: Type of generated component (model, migration, etc.)
 * - file_name, file_path: Generated file location and naming
 * - class_name, namespace: PHP class information
 * - configuration: JSON configuration used for generation
 * - generated_code: Complete generated source code
 * - template_used: Template identifier for generation
 * - success: Boolean status of generation operation
 * - generation_time_ms: Performance timing in milliseconds
 *
 * Relationships:
 * - Parent-child generations for complex code generation workflows
 * - User attribution for tracking generation ownership
 * - Hierarchical generation support for related components
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class CodeGenerationHistory extends Model
{
    use HasFactory;

    protected $table = 'code_generation_histories';

    protected $fillable = [
        'generation_id',
        'type',
        'file_name',
        'file_path',
        'class_name',
        'namespace',
        'configuration',
        'generated_code',
        'template_used',
        'file_size',
        'success',
        'error_message',
        'generation_time_ms',
        'user_id',
        'parent_generation_id',
        'metadata',
    ];

    protected $casts = [
        'configuration' => 'array',
        'metadata' => 'array',
        'success' => 'boolean',
        'file_size' => 'integer',
        'generation_time_ms' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_generation_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_generation_id');
    }

    public function user()
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');

        return $this->belongsTo($userModel);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (! $this->file_size) {
            return 'N/A';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getFormattedGenerationTimeAttribute(): string
    {
        if (! $this->generation_time_ms) {
            return 'N/A';
        }

        if ($this->generation_time_ms < 1000) {
            return $this->generation_time_ms.'ms';
        }

        return round($this->generation_time_ms / 1000, 2).'s';
    }

    public function canRegenerate(): bool
    {
        return $this->success && ! empty($this->configuration);
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'migration' => 'info',
            'model' => 'success',
            'factory' => 'warning',
            'seeder' => 'primary',
            'policy' => 'secondary',
            'resource' => 'danger',
            'controller' => 'gray',
            'complete' => 'indigo',
            default => 'gray',
        };
    }
}
