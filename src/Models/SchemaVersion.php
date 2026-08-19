<?php

namespace HkDevs\CodeForgeStudio\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Schema Version Model
 *
 * Manages versioned schema designs for the CodeForge Database Studio.
 * Allows users to save, load, and manage different versions of their database schemas.
 *
 * Features:
 * ✨ Version Management - Save multiple versions of schema designs
 * 🔄 Schema Persistence - Store complete schema data as JSON
 * 👤 User Association - Link schema versions to specific users
 * 🗄️ Connection Tracking - Track which database connection the schema belongs to
 * 📊 Metadata Storage - Store additional metadata about schema changes
 *
 * @author hardikkanajariya.in
 *
 * @version 2.0.0
 *
 * @since 1.0.0
 */
class SchemaVersion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'schema_versions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'connection',
        'name',
        'description',
        'schema_data',
        'metadata',
        'is_active',
        'version_number',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'schema_data' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate version number on creation
        static::creating(function (SchemaVersion $model) {
            if (empty($model->version_number)) {
                $model->version_number = $model->generateNextVersionNumber();
            }
        });
    }

    /**
     * Get the user that owns the schema version.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get versions for a specific connection.
     */
    public function scopeForConnection(Builder $query, string $connection): Builder
    {
        return $query->where('connection', $connection);
    }

    /**
     * Scope to get versions for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get only active versions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get latest versions first.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Generate the next version number for this connection and user.
     */
    protected function generateNextVersionNumber(): int
    {
        $lastVersion = static::where('user_id', $this->user_id)
            ->where('connection', $this->connection)
            ->max('version_number');

        return ($lastVersion ?? 0) + 1;
    }

    /**
     * Get formatted version display string.
     */
    public function getVersionDisplayAttribute(): string
    {
        return "v{$this->version_number}";
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M j, Y g:i A');
    }

    /**
     * Get table count from schema data.
     */
    public function getTableCountAttribute(): int
    {
        return count($this->schema_data['tables'] ?? []);
    }

    /**
     * Get relationship count from schema data.
     */
    public function getRelationshipCountAttribute(): int
    {
        return count($this->schema_data['relationships'] ?? []);
    }

    /**
     * Get schema summary.
     */
    public function getSchemaSummaryAttribute(): array
    {
        $schemaData = $this->schema_data ?? [];

        return [
            'tables' => count($schemaData['tables'] ?? []),
            'relationships' => count($schemaData['relationships'] ?? []),
            'columns' => collect($schemaData['tables'] ?? [])
                ->sum(fn ($table) => count($table['columns'] ?? [])),
        ];
    }

    /**
     * Check if this version contains a specific table.
     */
    public function hasTable(string $tableName): bool
    {
        $tables = $this->schema_data['tables'] ?? [];

        return collect($tables)->contains('name', $tableName);
    }

    /**
     * Get a specific table from the schema.
     */
    public function getSchemaTable(string $tableName): ?array
    {
        $tables = $this->schema_data['tables'] ?? [];

        return collect($tables)->firstWhere('name', $tableName);
    }

    /**
     * Clone this version with a new name.
     */
    public function duplicate(string $newName, ?string $description = null): self
    {
        $clone = $this->replicate();
        $clone->name = $newName;
        $clone->description = $description ?? "Copy of {$this->name}";
        $clone->is_active = true;
        $clone->save();

        return $clone;
    }

    /**
     * Export schema data as Laravel migration code.
     */
    public function exportAsMigration(?string $migrationName = null): string
    {
        $migrationName = $migrationName ?? Str::snake($this->name);
        $timestamp = now()->format('Y_m_d_His');
        $className = Str::studly($migrationName);

        $tables = $this->schema_data['tables'] ?? [];
        $relationships = $this->schema_data['relationships'] ?? [];

        $migration = "<?php\n\n";
        $migration .= "use Illuminate\\Database\\Migrations\\Migration;\n";
        $migration .= "use Illuminate\\Database\\Schema\\Blueprint;\n";
        $migration .= "use Illuminate\\Support\\Facades\\Schema;\n\n";
        $migration .= "return new class extends Migration\n{\n";
        $migration .= "    /**\n     * Run the migrations.\n     */\n";
        $migration .= "    public function up(): void\n    {\n";

        // Generate table creation code
        foreach ($tables as $table) {
            $migration .= $this->generateTableMigrationCode($table);
        }

        // Generate foreign key constraints
        foreach ($relationships as $relationship) {
            $migration .= $this->generateRelationshipMigrationCode($relationship);
        }

        $migration .= "    }\n\n";
        $migration .= "    /**\n     * Reverse the migrations.\n     */\n";
        $migration .= "    public function down(): void\n    {\n";

        // Generate drop statements in reverse order
        foreach (array_reverse($tables) as $table) {
            $migration .= "        Schema::dropIfExists('{$table['name']}');\n";
        }

        $migration .= "    }\n};\n";

        return $migration;
    }

    /**
     * Generate migration code for a table.
     */
    protected function generateTableMigrationCode(array $table): string
    {
        $code = "        Schema::create('{$table['name']}', function (Blueprint \$table) {\n";

        foreach ($table['columns'] as $column) {
            $code .= $this->generateColumnMigrationCode($column);
        }

        // Add indexes
        foreach ($table['indexes'] ?? [] as $index) {
            if ($index['type'] === 'primary') {
                continue;
            } // Primary key is handled by id() method

            if ($index['unique']) {
                $code .= "            \$table->unique(['".implode("', '", $index['columns'])."']);\n";
            } else {
                $code .= "            \$table->index(['".implode("', '", $index['columns'])."']);\n";
            }
        }

        $code .= "        });\n\n";

        return $code;
    }

    /**
     * Generate migration code for a column.
     */
    protected function generateColumnMigrationCode(array $column): string
    {
        $name = $column['name'];
        $type = $column['type'];

        // Map database types to Laravel migration methods
        $typeMap = [
            'bigint' => 'bigInteger',
            'int' => 'integer',
            'varchar' => 'string',
            'text' => 'text',
            'datetime' => 'dateTime',
            'date' => 'date',
            'boolean' => 'boolean',
            'decimal' => 'decimal',
            'json' => 'json',
        ];

        $laravelType = $typeMap[$type] ?? 'string';

        if ($name === 'id' && ($column['autoIncrement'] ?? false)) {
            $code = '            $table->id()';
        } else {
            $code = "            \$table->{$laravelType}('{$name}')";
        }

        if ($column['nullable'] ?? false) {
            $code .= '->nullable()';
        }

        if (! empty($column['default'])) {
            $code .= "->default('{$column['default']}')";
        }

        if ($column['unique'] ?? false) {
            $code .= '->unique()';
        }

        $code .= ";\n";

        return $code;
    }

    /**
     * Generate migration code for a relationship.
     */
    protected function generateRelationshipMigrationCode(array $relationship): string
    {
        $fromTable = $relationship['from'];
        $toTable = $relationship['to'];
        $fromColumn = $relationship['fromColumn'];
        $toColumn = $relationship['toColumn'];

        $code = "        Schema::table('{$fromTable}', function (Blueprint \$table) {\n";
        $code .= "            \$table->foreign('{$fromColumn}')\n";
        $code .= "                ->references('{$toColumn}')\n";
        $code .= "                ->on('{$toTable}')\n";
        $code .= "                ->onDelete('cascade');\n";
        $code .= "        });\n\n";

        return $code;
    }
}
