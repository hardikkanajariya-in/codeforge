<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use Illuminate\Console\Command;

/**
 * DebugSeederDiscoveryCommand
 *
 * Debug command to understand seeder discovery paths and help troubleshoot
 * discovery issues in different environments.
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class DebugSeederDiscoveryCommand extends Command
{
    protected $signature = 'codeforge:debug-discovery';

    protected $description = 'Debug seeder discovery paths and results';

    public function handle(): int
    {
        $this->info('🔍 Debug Seeder Discovery');
        $this->newLine();

        // Debug Laravel context
        $this->info('📍 Laravel Context:');
        try {
            if (function_exists('base_path')) {
                $this->line('   Base path: '.base_path());
            } else {
                $this->line('   Base path: Not available');
            }

            if (function_exists('database_path')) {
                $this->line('   Database path: '.database_path());
                $this->line('   Seeders path: '.database_path('seeders'));
                $this->line('   Seeders dir exists: '.(is_dir(database_path('seeders')) ? 'Yes' : 'No'));
            } else {
                $this->line('   Database path: Not available');
            }

            if (function_exists('app_path')) {
                $this->line('   App path: '.app_path());
            } else {
                $this->line('   App path: Not available');
            }
        } catch (\Exception $e) {
            $this->line('   Error: '.$e->getMessage());
        }
        $this->newLine();

        // Debug current working directory
        $this->info('📁 Working Directory:');
        $this->line('   Current directory: '.getcwd());
        $this->line('   Directory contents:');
        foreach (glob(getcwd().'/*') as $item) {
            $type = is_dir($item) ? '[DIR]' : '[FILE]';
            $this->line("     $type ".basename($item));
        }
        $this->newLine();

        // Debug discovery service
        $this->info('🔍 Discovery Service Results:');
        try {
            $service = app(SeederExecutionService::class);
            $discovered = $service->discoverSeeders();

            if (empty($discovered)) {
                $this->warn('   No seeders discovered');
            } else {
                foreach ($discovered as $seeder) {
                    $this->line("   Found: {$seeder['name']} ({$seeder['class_name']})");
                    $this->line("     File: {$seeder['file_path']}");
                    $this->line('     Exists: '.(file_exists($seeder['file_path']) ? 'Yes' : 'No'));
                }
            }
        } catch (\Exception $e) {
            $this->error('   Error: '.$e->getMessage());
        }
        $this->newLine();

        // Check for common seeder locations
        $this->info('📍 Common Seeder Locations:');
        $locations = [
            getcwd().'/database/seeders',
            getcwd().'/database/seeds',
            getcwd().'/app/Database/Seeders',
            getcwd().'/src/Database/Seeders',
        ];

        foreach ($locations as $location) {
            $exists = is_dir($location);
            $this->line("   $location: ".($exists ? 'EXISTS' : 'Not found'));

            if ($exists) {
                $files = glob($location.'/*.php');
                if (! empty($files)) {
                    foreach ($files as $file) {
                        $this->line('     - '.basename($file));
                    }
                } else {
                    $this->line('     (empty)');
                }
            }
        }

        return 0;
    }
}
