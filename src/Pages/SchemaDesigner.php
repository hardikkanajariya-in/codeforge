<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use HkDevs\CodeForgeStudio\Models\SchemaVersion;

class SchemaDesigner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string $view = 'codeforge-studio::pages.schema-designer';
    protected static ?string $navigationLabel = 'Schema Designer';
    protected static ?string $title = 'Database Schema Designer';
    protected static ?int $navigationSort = 3;

    public string $selectedConnection = '';
    public array $connectionInfo = [];
    public array $schemaData = [];
    public ?int $currentVersionId = null;
    public array $versionHistory = [];

    public function mount(): void
    {
        $this->selectedConnection = config('database.default');
        $this->loadConnectionInfo();
        $this->loadSchemaData(true); // Force fresh load to skip cached data initially
        $this->loadVersionHistory();

        Log::info('SchemaDesigner mounted', [
            'connection' => $this->selectedConnection,
            'database' => $this->connectionInfo['database'] ?? 'unknown',
            'tables_loaded' => count($this->schemaData['tables'] ?? []),
            'relationships_loaded' => count($this->schemaData['relationships'] ?? [])
        ]);
    }

    protected function loadConnectionInfo(): void
    {
        $connectionConfig = config("database.connections.{$this->selectedConnection}", []);

        $this->connectionInfo = [
            'name' => $this->selectedConnection,
            'driver' => $connectionConfig['driver'] ?? 'unknown',
            'database' => $connectionConfig['database'] ?? 'N/A',
            'host' => $connectionConfig['host'] ?? 'localhost',
            'port' => $connectionConfig['port'] ?? 'default',
        ];
    }

    protected function loadSchemaData(bool $forceRefresh = false): void
    {
        try {
            // Check if schema_versions table exists first
            if (!$forceRefresh && Schema::hasTable('schema_versions')) {
                // Load existing schema from database
                $latestVersion = DB::table('schema_versions')
                    ->where('connection', $this->selectedConnection)
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestVersion) {
                    $this->currentVersionId = $latestVersion->id;
                    $this->schemaData = json_decode($latestVersion->schema_data, true);
                    return;
                }
            }

            // Load current database schema
            $this->schemaData = $this->analyzeDatabaseSchema();
        } catch (\Exception $e) {
            Log::error('Failed to load schema data', [
                'error' => $e->getMessage(),
                'connection' => $this->selectedConnection
            ]);
            $this->schemaData = ['tables' => [], 'relationships' => []];
        }
    }

    protected function analyzeDatabaseSchema(): array
    {
        $tables = [];
        $relationships = [];

        try {
            // Ensure we're using the correct connection
            $schemaBuilder = Schema::connection($this->selectedConnection);
            $databaseName = $this->connectionInfo['database'];

            // Get table names using connection-specific raw queries for better database filtering
            $tableNames = [];
            $driver = $this->connectionInfo['driver'];

            if ($driver === 'mysql') {
                // For MySQL, get tables only from the specific database with explicit connection
                $rawTables = DB::connection($this->selectedConnection)
                    ->select("
                        SELECT TABLE_NAME 
                        FROM information_schema.TABLES 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_TYPE = 'BASE TABLE'
                        ORDER BY TABLE_NAME
                    ", [$databaseName]);

                $allTableNames = array_column($rawTables, 'TABLE_NAME');

                // Debug: Log what we found
                Log::info("Found " . count($allTableNames) . " total tables in database '{$databaseName}'", [
                    'database' => $databaseName,
                    'connection' => $this->selectedConnection,
                    'sample_tables' => array_slice($allTableNames, 0, 10)
                ]);

                $tableNames = $allTableNames;
            } else {
                // Fallback to Laravel's method for other drivers
                $schemaTables = $schemaBuilder->getTables();
                foreach ($schemaTables as $table) {
                    if (is_array($table)) {
                        $tableName = $table['name'] ?? $table['table_name'] ?? $table[0];
                        // For other drivers, check database matching if available
                        if (isset($table['table_schema']) || isset($table['database'])) {
                            $tableDatabase = $table['table_schema'] ?? $table['database'];
                            if ($tableDatabase !== $databaseName) {
                                continue; // Skip tables from other databases
                            }
                        }
                        $tableNames[] = $tableName;
                    } else {
                        $tableNames[] = $table;
                    }
                }
            }

            // Filter out system tables, framework tables, and plugin-specific tables
            $userTables = array_filter($tableNames, function ($tableName) {
                // Skip common system tables
                $systemTables = [
                    'information_schema',
                    'performance_schema',
                    'mysql',
                    'sys',
                    'sqlite_master',
                    'sqlite_sequence',
                    'sqlite_temp_master',
                    'pg_catalog',
                    'pg_toast',
                    'pg_stat',
                    'pg_settings'
                ];

                // Skip Laravel/framework tables that might clutter the view
                $frameworkTables = [
                    'migrations',
                    'password_resets',
                    'password_reset_tokens',
                    'personal_access_tokens',
                    'failed_jobs',
                    'telescope_entries',
                    'telescope_entries_tags',
                    'telescope_monitoring',
                    'cache',
                    'cache_locks',
                    'jobs',
                    'job_batches',
                    'sessions'
                ];

                // Skip CodeForge Studio plugin tables
                $pluginTables = [
                    'database_manager_logs',
                    'migration_histories',
                    'query_performance_logs',
                    'database_health_metrics',
                    'data_seeders',
                    'seeder_execution_logs',
                    'data_generation_templates',
                    'documentation_generations',
                    'schema_snapshots',
                    'schema_versions',
                    'code_generation_histories'
                ];

                $tableLower = strtolower($tableName);

                // Skip tables that start with system prefixes
                foreach ($systemTables as $systemTable) {
                    if (str_starts_with($tableLower, strtolower($systemTable))) {
                        return false;
                    }
                }

                // Skip known framework tables
                foreach ($frameworkTables as $frameworkTable) {
                    if ($tableLower === strtolower($frameworkTable)) {
                        return false;
                    }
                }

                // Skip plugin-specific tables
                foreach ($pluginTables as $pluginTable) {
                    if ($tableLower === strtolower($pluginTable)) {
                        return false;
                    }
                }

                return true;
            });

            // Debug: Log filtering results
            Log::info("After filtering: " . count($userTables) . " user tables remaining", [
                'filtered_count' => count($userTables),
                'sample_filtered' => array_slice($userTables, 0, 10)
            ]);

            foreach ($userTables as $tableName) {
                try {
                    // Get columns for this table using the specific connection
                    $columns = $schemaBuilder->getColumns($tableName);
                    $indexes = $schemaBuilder->getIndexes($tableName);

                    $tableData = [
                        'name' => $tableName,
                        'columns' => [],
                        'indexes' => [],
                        'position' => ['x' => rand(50, 500), 'y' => rand(50, 500)]
                    ];

                    foreach ($columns as $column) {
                        $tableData['columns'][] = [
                            'name' => $column['name'],
                            'type' => $column['type_name'] ?? $column['type'] ?? 'varchar',
                            'nullable' => $column['nullable'] ?? true,
                            'default' => $column['default'] ?? null,
                            'autoIncrement' => $column['auto_increment'] ?? false,
                        ];
                    }

                    foreach ($indexes as $index) {
                        $tableData['indexes'][] = [
                            'name' => $index['name'],
                            'columns' => $index['columns'],
                            'type' => $index['type'] ?? 'index',
                            'unique' => $index['unique'] ?? false,
                        ];
                    }

                    $tables[$tableName] = $tableData;

                    // Detect relationships based on foreign key naming convention
                    foreach ($tableData['columns'] as $column) {
                        if (Str::endsWith($column['name'], '_id') && $column['name'] !== 'id') {
                            $relatedTable = Str::plural(Str::beforeLast($column['name'], '_id'));
                            if (isset($tables[$relatedTable]) || in_array($relatedTable, $userTables)) {
                                $relationships[] = [
                                    'from' => $tableName,
                                    'to' => $relatedTable,
                                    'fromColumn' => $column['name'],
                                    'toColumn' => 'id',
                                    'type' => 'belongsTo',
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Skip tables that we can't read (permissions, etc.)
                    continue;
                }
            }
        } catch (\Exception $e) {
            // If we can't connect or read the schema, return empty
            return ['tables' => [], 'relationships' => []];
        }

        return [
            'tables' => array_values($tables),
            'relationships' => $relationships,
        ];
    }

    public function getConnectionDisplayProperty(): string
    {
        return "{$this->connectionInfo['name']} ({$this->connectionInfo['driver']}) - {$this->connectionInfo['database']}";
    }

    public function getConnectionDetailsProperty(): array
    {
        return [
            'Connection Name' => $this->connectionInfo['name'],
            'Database Driver' => ucfirst($this->connectionInfo['driver']),
            'Database Name' => $this->connectionInfo['database'],
            'Host' => $this->connectionInfo['host'],
            'Port' => $this->connectionInfo['port'],
        ];
    }

    public function refreshSchema(): void
    {
        try {
            Log::info('RefreshSchema called - starting refresh process');

            // Force reload of schema data (bypass cached version)
            $this->loadSchemaData(true);

            // Reload version history
            $this->loadVersionHistory();

            Log::info('RefreshSchema - dispatching event', [
                'tables_count' => count($this->schemaData['tables'] ?? []),
                'relationships_count' => count($this->schemaData['relationships'] ?? [])
            ]);

            // Dispatch event to frontend with the new data in the correct format
            $this->dispatch('schema-loaded', [
                'tables' => $this->schemaData['tables'] ?? [],
                'relationships' => $this->schemaData['relationships'] ?? []
            ]);

            $tableCount = count($this->schemaData['tables'] ?? []);

            Notification::make()
                ->title('Schema Refreshed')
                ->body("Loaded {$tableCount} tables from database")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('RefreshSchema failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title('Refresh Failed')
                ->body('Error refreshing schema: ' . $e->getMessage())
                ->danger()
                ->send();

            Log::error('Schema refresh failed', [
                'error' => $e->getMessage(),
                'connection' => $this->selectedConnection,
                'database' => $this->connectionInfo['database']
            ]);
        }
    }

    public function updateFrontend(): void
    {
        Log::info('UpdateFrontend called', [
            'tables_count' => count($this->schemaData['tables'] ?? []),
            'relationships_count' => count($this->schemaData['relationships'] ?? [])
        ]);

        // Simple method to just update the frontend with current backend data
        $this->dispatch('schema-loaded', [
            'tables' => $this->schemaData['tables'] ?? [],
            'relationships' => $this->schemaData['relationships'] ?? []
        ]);
    }

    public function debugTableInfo(): void
    {
        try {
            $databaseName = $this->connectionInfo['database'];
            $driver = $this->connectionInfo['driver'];

            // Get raw table count
            if ($driver === 'mysql') {
                $rawTables = DB::connection($this->selectedConnection)
                    ->select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$databaseName]);
                $allTables = array_column($rawTables, 'TABLE_NAME');
            } else {
                $allTables = Schema::connection($this->selectedConnection)->getTableListing();
            }

            $debugInfo = [
                'Database Name' => $databaseName,
                'Connection' => $this->selectedConnection,
                'Driver' => $driver,
                'Total Tables Found' => count($allTables),
                'Sample Tables' => array_slice($allTables, 0, 20),
                'Current Schema Tables' => count($this->schemaData['tables'] ?? []),
                'Schema Data Sample' => array_slice($this->schemaData['tables'] ?? [], 0, 3)
            ];

            Log::info('Debug Table Info', $debugInfo);

            // Also dispatch current data to frontend
            $this->dispatch('schema-loaded', [
                'tables' => $this->schemaData['tables'] ?? [],
                'relationships' => $this->schemaData['relationships'] ?? []
            ]);

            Notification::make()
                ->title('Debug Info Logged')
                ->body("Found " . count($allTables) . " total tables. Current schema has " . count($this->schemaData['tables'] ?? []) . " tables. Check logs for details.")
                ->info()
                ->send();
        } catch (\Exception $e) {
            Log::error('Debug failed', ['error' => $e->getMessage()]);

            Notification::make()
                ->title('Debug Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function loadVersionHistory(): void
    {
        try {
            if (!Schema::hasTable('schema_versions')) {
                $this->versionHistory = [];
                return;
            }

            $this->versionHistory = SchemaVersion::forConnection($this->selectedConnection)
                ->forUser(auth()->id())
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($version) {
                    return [
                        'id' => $version->id,
                        'name' => $version->name,
                        'version' => $version->version_display,
                        'description' => $version->description,
                        'created_at' => $version->formatted_date,
                        'table_count' => $version->table_count,
                        'is_active' => $version->is_active,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            $this->versionHistory = [];
        }
    }

    public function saveSchema(array $schemaData, ?string $name = null): void
    {
        try {
            if (!Schema::hasTable('schema_versions')) {
                Notification::make()
                    ->title('Schema versions table not found')
                    ->body('Please run migrations first to enable schema saving.')
                    ->warning()
                    ->send();
                return;
            }

            $name = $name ?: 'Schema ' . now()->format('Y-m-d H:i:s');

            SchemaVersion::create([
                'user_id' => auth()->id(),
                'connection' => $this->selectedConnection,
                'name' => $name,
                'description' => 'Auto-saved schema design',
                'schema_data' => $schemaData,
                'metadata' => [
                    'created_by' => auth()->user()->name ?? 'Unknown',
                    'browser' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                ],
                'is_active' => true,
            ]);

            $this->loadVersionHistory();

            Notification::make()
                ->title('Schema Saved')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Save Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function loadVersion(int $versionId): void
    {
        try {
            if (!Schema::hasTable('schema_versions')) {
                Notification::make()
                    ->title('Schema versions table not found')
                    ->body('Please run migrations first.')
                    ->warning()
                    ->send();
                return;
            }

            $version = SchemaVersion::forUser(auth()->id())
                ->find($versionId);

            if ($version) {
                $this->currentVersionId = $versionId;
                $this->schemaData = $version->schema_data;

                $this->dispatch('schema-loaded', [
                    'tables' => $this->schemaData['tables'] ?? [],
                    'relationships' => $this->schemaData['relationships'] ?? []
                ]);

                Notification::make()
                    ->title('Version Loaded')
                    ->body("Loaded '{$version->name}' {$version->version_display}")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Version not found')
                    ->body('The requested version could not be found.')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Load Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function exportMigration(array $schemaData): void
    {
        try {
            $migrations = $this->generateMigrations($schemaData);

            $timestamp = now()->format('Y_m_d_His');
            $filename = "{$timestamp}_create_schema_from_designer.php";

            $content = "<?php\n\n";
            $content .= "use Illuminate\\Database\\Migrations\\Migration;\n";
            $content .= "use Illuminate\\Database\\Schema\\Blueprint;\n";
            $content .= "use Illuminate\\Support\\Facades\\Schema;\n\n";
            $content .= "return new class extends Migration\n{\n";
            $content .= "    public function up(): void\n    {\n";

            foreach ($migrations as $migration) {
                $content .= $migration . "\n";
            }

            $content .= "    }\n\n";
            $content .= "    public function down(): void\n    {\n";

            // Add drop statements in reverse order
            $tables = array_reverse($schemaData['tables']);
            foreach ($tables as $table) {
                $content .= "        Schema::dropIfExists('{$table['name']}');\n";
            }

            $content .= "    }\n};\n";

            // For now, we'll just show the content. In production, you'd save this to a file
            $this->dispatch('migration-generated', [
                'content' => $content,
                'filename' => $filename
            ]);

            Notification::make()
                ->title('Migration Generated')
                ->body('Migration file has been generated successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function generateMigrations(array $schemaData): array
    {
        $migrations = [];
        $tables = $schemaData['tables'] ?? [];
        $relationships = $schemaData['relationships'] ?? [];

        // First pass: Create tables without foreign keys
        foreach ($tables as $table) {
            $migration = "        Schema::create('{$table['name']}', function (Blueprint \$table) {\n";

            foreach ($table['columns'] as $column) {
                $migration .= $this->generateColumnDefinition($column);
            }

            // Add indexes
            foreach ($table['indexes'] ?? [] as $index) {
                if ($index['unique']) {
                    $migration .= "            \$table->unique(['" . implode("', '", $index['columns']) . "']);\n";
                } else {
                    $migration .= "            \$table->index(['" . implode("', '", $index['columns']) . "']);\n";
                }
            }

            $migration .= "        });\n";
            $migrations[] = $migration;
        }

        // Second pass: Add foreign keys
        $foreignKeyMigrations = [];
        foreach ($relationships as $relationship) {
            $migration = "        Schema::table('{$relationship['from']}', function (Blueprint \$table) {\n";
            $migration .= "            \$table->foreign('{$relationship['fromColumn']}')\n";
            $migration .= "                ->references('{$relationship['toColumn']}')\n";
            $migration .= "                ->on('{$relationship['to']}')\n";
            $migration .= "                ->onDelete('cascade');\n";
            $migration .= "        });\n";
            $foreignKeyMigrations[] = $migration;
        }

        return array_merge($migrations, $foreignKeyMigrations);
    }

    protected function generateColumnDefinition(array $column): string
    {
        $definition = "            \$table->";

        // Map column types to Laravel methods
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

        $laravelType = $typeMap[$column['type']] ?? 'string';

        if ($column['name'] === 'id' && $column['autoIncrement']) {
            $definition .= "id()";
        } else {
            $definition .= "{$laravelType}('{$column['name']}')";
        }

        if ($column['nullable']) {
            $definition .= "->nullable()";
        }

        if ($column['default'] !== null) {
            $definition .= "->default('{$column['default']}')";
        }

        if ($column['unique'] ?? false) {
            $definition .= "->unique()";
        }

        $definition .= ";\n";

        return $definition;
    }

    public function createDefaultTable(): array
    {
        return [
            'name' => 'new_table_' . Str::random(6),
            'columns' => [
                [
                    'name' => 'id',
                    'type' => 'bigint',
                    'nullable' => false,
                    'default' => null,
                    'autoIncrement' => true,
                    'unique' => true,
                ],
                [
                    'name' => 'created_at',
                    'type' => 'timestamp',
                    'nullable' => true,
                    'default' => null,
                ],
                [
                    'name' => 'updated_at',
                    'type' => 'timestamp',
                    'nullable' => true,
                    'default' => null,
                ],
            ],
            'indexes' => [
                [
                    'name' => 'primary',
                    'columns' => ['id'],
                    'type' => 'primary',
                    'unique' => true,
                ],
            ],
        ];
    }
}
