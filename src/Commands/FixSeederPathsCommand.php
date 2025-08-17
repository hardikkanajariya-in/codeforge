<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use Illuminate\Console\Command;

/**
 * FixSeederPathsCommand
 * 
 * Command to fix incorrect seeder file paths and cleanup invalid entries
 * in the data_seeders table. Useful when seeders were registered with
 * incorrect paths or when the Laravel project structure changes.
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class FixSeederPathsCommand extends Command
{
    protected $signature = 'codeforge:fix-seeder-paths 
                            {--cleanup : Also remove invalid seeders that cannot be found}
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Fix incorrect seeder file paths and cleanup invalid entries';

    public function handle(): int
    {
        $this->info('🔧 Fixing Seeder Paths and Cleaning Invalid Entries');
        $this->newLine();

        try {
            $dryRun = $this->option('dry-run');
            $cleanup = $this->option('cleanup');

            // Get all current seeders
            $currentSeeders = DataSeeder::all();
            $this->info("📋 Found {$currentSeeders->count()} registered seeders");

            if ($currentSeeders->isEmpty()) {
                $this->info('ℹ️  No seeders to process');
                return 0;
            }

            // Discover actual seeders
            $service = app(SeederExecutionService::class);
            $discoveredSeeders = collect($service->discoverSeeders());
            $this->info("🔍 Discovered {$discoveredSeeders->count()} actual seeders");
            $this->newLine();

            $fixed = 0;
            $removed = 0;
            $created = 0;

            // Process existing seeders
            foreach ($currentSeeders as $seeder) {
                $this->line("🔍 Checking: {$seeder->name} ({$seeder->class_name})");

                // Find matching discovered seeder
                $discovered = $discoveredSeeders->firstWhere('class_name', $seeder->class_name);

                if ($discovered) {
                    // Check if path needs updating
                    if ($seeder->file_path !== $discovered['file_path']) {
                        $this->warn("   📝 Path needs updating:");
                        $this->line("      Old: {$seeder->file_path}");
                        $this->line("      New: {$discovered['file_path']}");

                        if (!$dryRun) {
                            $seeder->update([
                                'file_path' => $discovered['file_path'],
                                'type' => $discovered['type'],
                            ]);
                        }
                        $fixed++;
                    } else {
                        $this->info("   ✅ Path is correct");
                    }
                } else {
                    // Seeder not found in discovery
                    $this->error("   ❌ Seeder not found in filesystem");

                    if ($cleanup) {
                        $this->warn("   🗑️  Will be removed");
                        if (!$dryRun) {
                            $seeder->delete();
                        }
                        $removed++;
                    } else {
                        $this->line("   ℹ️  Use --cleanup flag to remove invalid seeders");
                    }
                }
            }

            // Check for new seeders not in database
            foreach ($discoveredSeeders as $discovered) {
                $exists = $currentSeeders->firstWhere('class_name', $discovered['class_name']);

                if (!$exists) {
                    $this->info("🆕 New seeder found: {$discovered['name']}");

                    if (!$dryRun) {
                        DataSeeder::create($discovered);
                    }
                    $created++;
                }
            }

            $this->newLine();

            // Summary
            if ($dryRun) {
                $this->info('📊 Dry Run Summary (no changes made):');
            } else {
                $this->info('📊 Summary:');
            }

            $this->line("   Seeders with fixed paths: {$fixed}");
            $this->line("   New seeders added: {$created}");

            if ($cleanup) {
                $this->line("   Invalid seeders removed: {$removed}");
            } else {
                $invalidCount = $currentSeeders->count() - $discoveredSeeders->count() + $created;
                if ($invalidCount > 0) {
                    $this->line("   Invalid seeders found: {$invalidCount} (use --cleanup to remove)");
                }
            }

            if (!$dryRun && ($fixed > 0 || $created > 0 || $removed > 0)) {
                $this->newLine();
                $this->info('✅ Seeder paths have been fixed!');
                $this->line('💡 You can now run: php artisan codeforge:diagnose-seeders --auto');
            } else if ($dryRun) {
                $this->newLine();
                $this->info('💡 Run without --dry-run to apply changes');
            } else {
                $this->newLine();
                $this->info('✅ All seeder paths are already correct!');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
