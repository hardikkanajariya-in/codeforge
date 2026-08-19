<?php

namespace HkDevs\CodeForgeStudio\Models;

use HkDevs\CodeForgeStudio\Database\Factories\DocumentationGenerationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DocumentationGeneration
 *
 * Eloquent model for managing database documentation generation processes
 * with comprehensive tracking, versioning, and multi-format support.
 *
 * Key Features:
 * - Multi-format documentation generation (HTML, PDF, Markdown, JSON)
 * - Selective table inclusion with scope configuration
 * - Version tracking and documentation history management
 * - Schema snapshot integration for point-in-time documentation
 * - Comprehensive metadata and options storage
 * - Error tracking and generation status monitoring
 *
 * Database Fields:
 * - title: Documentation title and identifier
 * - description: Documentation purpose and content description
 * - version: Documentation version for tracking changes
 * - format: Output format (html, pdf, markdown, json)
 * - scope: Documentation scope (full, partial, tables, views)
 * - included_tables: Array of specific tables to document
 * - options: JSON configuration for generation customization
 * - file_path: Generated documentation file location
 * - file_size: Generated file size for storage tracking
 * - metadata: Additional generation context and information
 * - status: Generation status (pending, completed, failed)
 * - schema_snapshot_id: Reference to schema state at generation time
 *
 * Generation Features:
 * - Automated schema analysis and relationship mapping
 * - Custom styling and branding for professional documentation
 * - Export capabilities with multiple delivery methods
 * - Integration with schema versioning for accurate documentation
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class DocumentationGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'version',
        'format',
        'scope',
        'included_tables',
        'options',
        'file_path',
        'file_size',
        'metadata',
        'status',
        'error_message',
        'generated_at',
        'generated_by',
        'schema_snapshot_id',
    ];

    protected static function newFactory()
    {
        return DocumentationGenerationFactory::new();
    }

    protected $casts = [
        'included_tables' => 'array',
        'options' => 'array',
        'metadata' => 'array',
        'generated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'format' => 'markdown',
        'scope' => 'full_schema',
        'version' => '1.0.0',
    ];

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByFormat($query, string $format)
    {
        return $query->where('format', $format);
    }

    public function scopeByScope($query, string $scope)
    {
        return $query->where('scope', $scope);
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

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'success',
            'failed' => 'danger',
            'generating' => 'warning',
            'pending' => 'gray',
            default => 'gray',
        };
    }

    public function getFormatBadgeColorAttribute(): string
    {
        return match ($this->format) {
            'markdown' => 'info',
            'html' => 'success',
            'pdf' => 'warning',
            default => 'gray',
        };
    }

    public function getScopeDisplayAttribute(): string
    {
        return match ($this->scope) {
            'full_schema' => 'Full Database Schema',
            'selected_tables' => 'Selected Tables ('.count($this->included_tables ?? []).')',
            'single_table' => 'Single Table',
            'models_only' => 'Models Only',
            default => 'Unknown Scope',
        };
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if (! $this->file_path || $this->status !== 'completed') {
            return null;
        }

        return route('admin.database-manager.documentation.download', $this->id);
    }

    public function markAsGenerating(): void
    {
        $this->update([
            'status' => 'generating',
        ]);
    }

    public function markAsCompleted(string $filePath, int $fileSize, array $metadata = []): void
    {
        $this->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'metadata' => $metadata,
            'generated_at' => now(),
            'error_message' => null,
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
