<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DataGenerationTemplate
 *
 * Eloquent model for managing data generation templates that define
 * how to create realistic test data for database tables.
 *
 * Key Features:
 * - Configurable field mappings for data generation
 * - Relationship-aware data generation with foreign key support
 * - Constraint validation and data consistency rules
 * - Reusable templates with activation status
 * - Sample data storage for generation examples
 * - User attribution for template ownership
 *
 * Database Fields:
 * - name: Template identifier and display name
 * - description: Template purpose and usage information
 * - table_name: Target database table for data generation
 * - field_mappings: JSON configuration for field data types
 * - relationships: Related table and foreign key definitions
 * - constraints: Validation rules and data constraints
 * - default_count: Default number of records to generate
 * - sample_data: Example data for template testing
 * - is_active: Template activation status
 *
 * Template Features:
 * - Smart field type detection and appropriate data generation
 * - Relationship handling for referential integrity
 * - Custom data patterns and realistic value generation
 * - Configurable data volume and distribution
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class DataGenerationTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'table_name',
        'field_mappings',
        'relationships',
        'constraints',
        'default_count',
        'sample_data',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'field_mappings' => 'array',
        'relationships' => 'array',
        'constraints' => 'array',
        'sample_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'success' : 'gray';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForTable($query, string $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    public function getFieldMappingForColumn(string $column): array
    {
        return $this->field_mappings[$column] ?? [];
    }

    public function hasRelationships(): bool
    {
        return ! empty($this->relationships);
    }

    public function hasConstraints(): bool
    {
        return ! empty($this->constraints);
    }

    public function getRelationshipsCount(): int
    {
        return count($this->relationships ?? []);
    }

    public function getFieldsCount(): int
    {
        return count($this->field_mappings ?? []);
    }

    public function canGenerate(): bool
    {
        return $this->is_active && ! empty($this->field_mappings);
    }
}
