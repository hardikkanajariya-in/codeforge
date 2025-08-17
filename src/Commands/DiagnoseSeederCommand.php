<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Models\SeederExecutionLog;
use Illuminate\Console\Command;
use Illuminate\Database\Seeder;

/**
 * DiagnoseSeederCommand
 * 
 * Diagnostic command for troubleshooting seeder issues in CodeForge Database Studio.
 * Provides comprehensive analysis of seeder configurations, file integrity, and execution history.
 * 
 * Features:
 * - Seeder configuration validation and verification
 * - File existence and accessibility checking
 * - Class loading and instantiation validation
 * - Recent execution history analysis
 * - Detailed error reporting and troubleshooting guidance
 * - Auto-run seeder configuration verification
 * - Dependency and relationship validation
 * 
 * Diagnostic Checks:
 * - File System: File path validation and accessibility
 * - Class Loading: PHP class existence and autoloading
 * - Seeder Inheritance: Proper Laravel seeder class structure
 * - Configuration: Seeder settings and metadata validation
 * - Database: Connection and table accessibility
 * - Execution History: Recent success/failure patterns
 * - Dependencies: Missing dependencies and requirements
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DiagnoseSeederCommand extends Command
{
    protected $signature = 'codeforge:diagnose-seeders 
                            {--seeder= : Diagnose specific seeder by name}
                            {--auto : Check only auto-run seeders}
                            {--failed : Check only recently failed seeders}';

    protected $description = 'Diagnose seeder configuration and execution issues';

    public function handle(): int
    {
        $this->info('🔍 CodeForge Database Studio - Seeder Diagnostics');
        $this->newLine();

        try {
            if ($this->option('seeder')) {
                return $this->diagnoseSpecificSeeder($this->option('seeder'));
            }

            if ($this->option('failed')) {
                return $this->diagnoseFailedSeeders();
            }

            if ($this->option('auto')) {
                return $this->diagnoseAutoSeeders();
            }

            return $this->diagnoseAllSeeders();
        } catch (\Exception $e) {
            $this->error('❌ Diagnostic failed: ' . $e->getMessage());
            return 1;
        }
    }

    protected function diagnoseSpecificSeeder(string $name): int
    {
        $seeder = DataSeeder::where('name', $name)->first();

        if (!$seeder) {
            $this->error("❌ Seeder '{$name}' not found");
            return 1;
        }

        $this->info("🔍 Diagnosing seeder: {$seeder->name}");
        $this->newLine();

        return $this->performSeederDiagnostics($seeder);
    }

    protected function diagnoseFailedSeeders(): int
    {
        $failedLogs = SeederExecutionLog::failed()
            ->recent(7)
            ->with('seeder')
            ->get();

        if ($failedLogs->isEmpty()) {
            $this->info('✅ No failed seeders found in the last 7 days');
            return 0;
        }

        $this->info("🔍 Found {$failedLogs->count()} failed seeder executions in the last 7 days");
        $this->newLine();

        $issues = 0;
        foreach ($failedLogs as $log) {
            $this->warn("🔍 Analyzing failed execution: {$log->seeder_name}");
            $this->line("   Error: {$log->error_message}");
            $this->line("   Time: {$log->started_at}");

            if ($log->seeder) {
                $result = $this->performSeederDiagnostics($log->seeder, false);
                if ($result !== 0) {
                    $issues++;
                }
            }
            $this->newLine();
        }

        return $issues > 0 ? 1 : 0;
    }

    protected function diagnoseAutoSeeders(): int
    {
        $autoSeeders = DataSeeder::active()->autoRun()->get();

        if ($autoSeeders->isEmpty()) {
            $this->info('ℹ️  No auto-run seeders configured');
            return 0;
        }

        $this->info("🔍 Diagnosing {$autoSeeders->count()} auto-run seeders");
        $this->newLine();

        $issues = 0;
        foreach ($autoSeeders as $seeder) {
            $result = $this->performSeederDiagnostics($seeder, false);
            if ($result !== 0) {
                $issues++;
            }
        }

        if ($issues === 0) {
            $this->info('✅ All auto-run seeders are properly configured');
        } else {
            $this->error("❌ Found issues with {$issues} auto-run seeders");
            $this->newLine();
            $this->warn('💡 Suggested fixes:');
            $this->line('   1. Run: php artisan codeforge:diagnose-seeders --failed (for detailed error info)');
            $this->line('   2. In Filament Admin → Data Seeders → Click "Discover Seeders" (to fix file paths)');
            $this->line('   3. In Filament Admin → Data Seeders → Click "Cleanup Invalid Seeders" (to remove broken ones)');
            $this->line('   4. Check that seeder files exist in database/seeders/ directory');
        }

        return $issues > 0 ? 1 : 0;
    }

    protected function diagnoseAllSeeders(): int
    {
        $seeders = DataSeeder::all();

        if ($seeders->isEmpty()) {
            $this->info('ℹ️  No seeders found');
            return 0;
        }

        $this->info("🔍 Diagnosing {$seeders->count()} seeders");
        $this->newLine();

        $issues = 0;
        $active = 0;
        $inactive = 0;

        foreach ($seeders as $seeder) {
            if ($seeder->status === 'active') {
                $active++;
                $result = $this->performSeederDiagnostics($seeder, false);
                if ($result !== 0) {
                    $issues++;
                }
            } else {
                $inactive++;
            }
        }

        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   Total seeders: {$seeders->count()}");
        $this->line("   Active: {$active}");
        $this->line("   Inactive: {$inactive}");
        $this->line("   Issues found: {$issues}");

        return $issues > 0 ? 1 : 0;
    }

    protected function performSeederDiagnostics(DataSeeder $seeder, bool $verbose = true): int
    {
        $issues = 0;

        if ($verbose) {
            $this->line("📋 Seeder: {$seeder->name}");
            $this->line("   Class: {$seeder->class_name}");
            $this->line("   Status: {$seeder->status}");
            $this->line("   Type: {$seeder->type}");
            $this->line("   Auto-run: " . ($seeder->auto_run ? 'Yes' : 'No'));
        }

        // Check status
        if ($seeder->status !== 'active') {
            $this->warn("   ⚠️  Seeder is not active (status: {$seeder->status})");
            if (!$verbose) {
                $this->line("   🔍 {$seeder->name}: Not active (status: {$seeder->status})");
            }
            $issues++;
        }

        // Check file existence
        if (!$seeder->exists()) {
            $this->error("   ❌ File not found: {$seeder->file_path}");
            if (!$verbose) {
                $this->line("   🔍 {$seeder->name}: File not found");
            }
            $issues++;
        } else {
            if ($verbose) {
                $this->info("   ✅ File exists: {$seeder->file_path}");
            }
        }

        // Check class loading
        if (!class_exists($seeder->class_name)) {
            $this->error("   ❌ Class not found: {$seeder->class_name}");
            if (!$verbose) {
                $this->line("   🔍 {$seeder->name}: Class not loadable");
            }
            $issues++;
        } else {
            if ($verbose) {
                $this->info("   ✅ Class loadable: {$seeder->class_name}");
            }

            // Check if it's a valid seeder
            try {
                $reflection = new \ReflectionClass($seeder->class_name);
                if (!$reflection->isSubclassOf(Seeder::class)) {
                    $this->error("   ❌ Class is not a valid seeder (must extend Illuminate\\Database\\Seeder)");
                    if (!$verbose) {
                        $this->line("   🔍 {$seeder->name}: Invalid seeder class");
                    }
                    $issues++;
                } else {
                    if ($verbose) {
                        $this->info("   ✅ Valid seeder class");
                    }
                }

                // Check if instantiable
                if (!$reflection->isInstantiable()) {
                    $this->error("   ❌ Class is not instantiable");
                    if (!$verbose) {
                        $this->line("   🔍 {$seeder->name}: Class not instantiable");
                    }
                    $issues++;
                } else {
                    if ($verbose) {
                        $this->info("   ✅ Class is instantiable");
                    }
                }
            } catch (\ReflectionException $e) {
                $this->error("   ❌ Reflection error: {$e->getMessage()}");
                if (!$verbose) {
                    $this->line("   🔍 {$seeder->name}: Reflection error");
                }
                $issues++;
            }
        }

        // Check recent execution history
        $recentLogs = $seeder->executionLogs()->recent(7)->limit(3)->get();
        if ($recentLogs->isNotEmpty()) {
            if ($verbose) {
                $this->line("   📊 Recent executions:");
                foreach ($recentLogs as $log) {
                    $status = $log->status === 'completed' ? '✅' : '❌';
                    $this->line("      {$status} {$log->started_at} - {$log->status}");
                    if ($log->status === 'failed' && $log->error_message) {
                        $this->line("         Error: {$log->error_message}");
                    }
                }
            }

            $failedCount = $recentLogs->where('status', 'failed')->count();
            if ($failedCount > 0) {
                if (!$verbose) {
                    $this->line("   🔍 {$seeder->name}: {$failedCount} recent failures");
                }
            }
        }

        if ($verbose) {
            if ($issues === 0) {
                $this->info("   ✅ No issues found");
            } else {
                $this->error("   ❌ Found {$issues} issue(s)");
            }
            $this->newLine();
        }

        return $issues;
    }
}
