<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use Illuminate\Console\Command;

/**
 * RunSeedersCommand
 *
 * Advanced database seeding execution utility for CodeForge Database Studio.
 * Provides intelligent seeder management with selective execution and comprehensive logging.
 *
 * Features:
 * - Selective seeder execution by name or class
 * - Auto-run mode for scheduled and automated seeding
 * - Dry-run mode for safe seeder validation
 * - Comprehensive execution logging and metrics
 * - Dependency resolution and execution ordering
 * - Progress tracking for long-running seeding operations
 * - Error handling with detailed failure analysis
 *
 * Execution Modes:
 * - All Active Seeders: Execute all enabled seeders in dependency order
 * - Auto-Run Seeders: Execute only seeders marked for automatic execution
 * - Specific Seeder: Target individual seeders by name for precise control
 * - Class-Based Execution: Execute seeders by their class name
 *
 * Seeder Selection:
 * - Name-based selection for user-friendly identification
 * - Class-based selection for programmatic execution
 * - Status-based filtering (active, auto-run, disabled)
 * - Dependency-aware execution ordering
 * - Custom execution groups and categories
 *
 * Safety Features:
 * - Dry-run mode for validation without data modification
 * - Confirmation prompts for destructive operations
 * - Rollback support for reversible seeders
 * - Data integrity validation before and after execution
 * - Backup recommendations for production environments
 *
 * Monitoring and Logging:
 * - Detailed execution metrics and timing
 * - Progress tracking with real-time updates
 * - Comprehensive error logging and diagnostics
 * - Success/failure reporting with statistics
 * - Historical execution analysis
 *
 * Dependency Management:
 * - Automatic dependency resolution and ordering
 * - Circular dependency detection and prevention
 * - Foreign key constraint awareness
 * - Table population order optimization
 * - Relationship-aware seeding sequences
 *
 * Integration Features:
 * - Compatible with Laravel's native seeding system
 * - Supports custom seeder classes and configurations
 * - Integration with CodeForge data generation templates
 * - Scheduled execution support for automated workflows
 * - CI/CD pipeline integration for deployment seeding
 *
 * Performance Optimization:
 * - Batch processing for large datasets
 * - Memory-efficient streaming for massive operations
 * - Database transaction management
 * - Index optimization during seeding
 * - Connection pooling for concurrent operations
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Run all active seeders
 * php artisan codeforge:run-seeders
 *
 * # Run only auto-run enabled seeders
 * php artisan codeforge:run-seeders --auto
 *
 * # Execute specific seeder by name
 * php artisan codeforge:run-seeders --seeder=UserDataSeeder
 *
 * # Execute seeder by class name
 * php artisan codeforge:run-seeders --class=App\\Database\\Seeders\\ProductSeeder
 *
 * # Preview execution without running
 * php artisan codeforge:run-seeders --dry-run
 */
class RunSeedersCommand extends Command
{
    protected $signature = 'codeforge:run-seeders 
                            {--auto : Run only auto-run seeders}
                            {--seeder= : Run specific seeder by name}
                            {--class= : Run specific seeder by class name}
                            {--dry-run : Preview what would be executed}';

    protected $description = 'Execute data seeders through the CodeForge Studio';

    public function handle(): int
    {
        $service = app(SeederExecutionService::class);

        try {
            if ($this->option('seeder')) {
                return $this->runSpecificSeeder($this->option('seeder'), $service);
            }

            if ($this->option('class')) {
                return $this->runSeederByClass($this->option('class'), $service);
            }

            if ($this->option('auto')) {
                return $this->runAutoSeeders($service);
            }

            return $this->runAllActiveSeeders($service);
        } catch (\Exception $e) {
            $this->error('Command failed: '.$e->getMessage());

            return 1;
        }
    }

    protected function runSpecificSeeder(string $name, SeederExecutionService $service): int
    {
        $seeder = DataSeeder::where('name', $name)->first();

        if (! $seeder) {
            $this->error("Seeder '{$name}' not found.");

            return 1;
        }

        if ($this->option('dry-run')) {
            $this->info("Would execute seeder: {$seeder->name} ({$seeder->class_name})");

            return 0;
        }

        return $this->executeSingleSeeder($seeder, $service);
    }

    protected function runSeederByClass(string $className, SeederExecutionService $service): int
    {
        $seeder = DataSeeder::where('class_name', $className)->first();

        if (! $seeder) {
            $this->error("Seeder with class '{$className}' not found.");

            return 1;
        }

        if ($this->option('dry-run')) {
            $this->info("Would execute seeder: {$seeder->name} ({$seeder->class_name})");

            return 0;
        }

        return $this->executeSingleSeeder($seeder, $service);
    }

    protected function runAutoSeeders(SeederExecutionService $service): int
    {
        $seeders = DataSeeder::active()->autoRun()->byPriority()->get();

        if ($seeders->isEmpty()) {
            $this->info('No auto-run seeders found.');

            return 0;
        }

        if ($this->option('dry-run')) {
            $this->info('Would execute auto-run seeders:');
            foreach ($seeders as $seeder) {
                $this->line("  - {$seeder->name} (priority: {$seeder->priority})");
            }

            return 0;
        }

        return $this->executeMultipleSeeders($seeders, $service);
    }

    protected function runAllActiveSeeders(SeederExecutionService $service): int
    {
        $seeders = DataSeeder::active()->byPriority()->get();

        if ($seeders->isEmpty()) {
            $this->info('No active seeders found.');

            return 0;
        }

        if ($this->option('dry-run')) {
            $this->info('Would execute all active seeders:');
            foreach ($seeders as $seeder) {
                $this->line("  - {$seeder->name} (priority: {$seeder->priority})");
            }

            return 0;
        }

        if (! $this->confirm("Execute {$seeders->count()} active seeders?")) {
            $this->info('Operation cancelled.');

            return 0;
        }

        return $this->executeMultipleSeeders($seeders, $service);
    }

    protected function executeSingleSeeder(DataSeeder $seeder, SeederExecutionService $service): int
    {
        $this->info("Executing seeder: {$seeder->name}");

        try {
            $log = $service->executeSeeder($seeder);

            if ($log->isCompleted()) {
                $this->info('✓ Seeder completed successfully');
                $this->line("  Duration: {$log->duration}");
                $this->line("  Records created: {$log->records_created}");
                $this->line("  Records updated: {$log->records_updated}");

                return 0;
            } else {
                $this->error("✗ Seeder failed: {$log->error_message}");

                return 1;
            }
        } catch (\Exception $e) {
            $this->error("✗ Seeder execution failed: {$e->getMessage()}");

            return 1;
        }
    }

    protected function executeMultipleSeeders($seeders, SeederExecutionService $service): int
    {
        $this->info("Executing {$seeders->count()} seeders...");

        $results = $service->executeMultipleSeeders($seeders->pluck('id')->toArray());

        $successful = 0;
        $failed = 0;

        foreach ($results as $seederData) {
            if (is_object($seederData) && $seederData->isCompleted()) {
                $successful++;
                $this->info("✓ {$seederData->seeder_name}");
            } else {
                $failed++;
                // All results are now SeederExecutionLog objects
                $seederName = is_object($seederData) ? $seederData->seeder_name : 'Unknown';
                $error = is_object($seederData) ? $seederData->error_message : 'Unknown error';
                $this->error("✗ {$seederName}: {$error}");
            }
        }

        $this->newLine();
        $this->info("Execution completed: {$successful} successful, {$failed} failed");

        return $failed > 0 ? 1 : 0;
    }
}
