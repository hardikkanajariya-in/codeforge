<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseStatsWidget extends BaseWidget
{
    protected static string $view = 'filament-widgets::stats-overview-widget';

    protected function getStats(): array
    {
        return [
            $this->getTablesCount(),
            $this->getPendingMigrationsCount(),
            $this->getDatabaseSize(),
        ];
    }

    protected function getTablesCount(): Stat
    {
        try {
            $connection = config('database.default');

            // Get tables using SHOW TABLES to get proper count
            $tables = DB::select('SHOW TABLES');
            $userTables = [];

            foreach ($tables as $table) {
                $tableArray = (array) $table;
                $tableName = array_values($tableArray)[0];

                // Skip system tables and plugin tables
                if (!in_array($tableName, [
                    'migrations',
                    'personal_access_tokens',
                    'password_reset_tokens',
                    'failed_jobs',
                    'cache',
                    'cache_locks',
                    'sessions',
                    'jobs',
                    'job_batches',
                    // Plugin tables
                    'data_seeders',
                    'seeder_execution_logs',
                    'data_generation_templates',
                    'database_manager_logs',
                    'database_health_metrics',
                    'migration_histories',
                    'query_performance_logs',
                ])) {
                    $userTables[] = $tableName;
                }
            }

            $count = count($userTables);
            $totalTables = count($tables);

            return Stat::make('User Tables', $count)
                ->description("({$totalTables} total tables)")
                ->descriptionIcon('heroicon-m-table-cells')
                ->color('success');
        } catch (\Exception $e) {
            return Stat::make('Total Tables', 'Error')
                ->description('Unable to count tables')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getPendingMigrationsCount(): Stat
    {
        try {
            // Get all migration files
            $migrationPath = database_path('migrations');
            $migrationFiles = glob($migrationPath . '/*.php');
            $totalMigrations = count($migrationFiles);

            // Get executed migrations
            $executedMigrations = 0;
            if (Schema::hasTable('migrations')) {
                $executedMigrations = DB::table('migrations')->count();
            }

            $pending = max(0, $totalMigrations - $executedMigrations);

            return Stat::make('Pending Migrations', $pending)
                ->description($executedMigrations . ' completed')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($pending > 0 ? 'warning' : 'success');
        } catch (\Exception $e) {
            return Stat::make('Pending Migrations', 'Error')
                ->description('Unable to check migrations')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getDatabaseSize(): Stat
    {
        try {
            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");

            $size = 'Unknown';
            $description = 'Size calculation not available';

            switch ($driver) {
                case 'mysql':
                    $result = DB::select("
                        SELECT 
                            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                        FROM information_schema.tables 
                        WHERE table_schema = DATABASE()
                    ");
                    if (!empty($result)) {
                        $size = $result[0]->size_mb . ' MB';
                        $description = 'Total database size';
                    }
                    break;

                case 'pgsql':
                    $result = DB::select("
                        SELECT 
                            ROUND(pg_database_size(current_database()) / 1024.0 / 1024.0, 2) AS size_mb
                    ");
                    if (!empty($result)) {
                        $size = $result[0]->size_mb . ' MB';
                        $description = 'Total database size';
                    }
                    break;

                case 'sqlite':
                    $dbPath = config("database.connections.{$connection}.database");
                    if (file_exists($dbPath)) {
                        $sizeBytes = filesize($dbPath);
                        $sizeMB = round($sizeBytes / 1024 / 1024, 2);
                        $size = $sizeMB . ' MB';
                        $description = 'Database file size';
                    }
                    break;
            }

            return Stat::make('Database Size', $size)
                ->description($description)
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('primary');
        } catch (\Exception $e) {
            return Stat::make('Database Size', 'Error')
                ->description('Unable to calculate size')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

}
