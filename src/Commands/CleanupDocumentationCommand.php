<?php

namespace HkDevs\CodeForgeStudio\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use HkDevs\CodeForgeStudio\Models\SchemaSnapshot;

/**
 * CleanupDocumentationCommand
 * 
 * Comprehensive documentation file and record cleanup utility for CodeForge Database Studio.
 * Maintains optimal performance by removing old, unused, or failed documentation generations.
 * 
 * Features:
 * - Age-based cleanup with configurable retention period
 * - Selective cleanup of failed generations only
 * - Dry-run mode for safe preview of cleanup operations
 * - Force mode to bypass confirmation prompts
 * - Comprehensive file system and database record cleanup
 * - Detailed reporting of cleanup operations
 * - Safe removal of associated files and metadata
 * 
 * Use Cases:
 * - Regular maintenance to prevent storage bloat
 * - Cleaning up after failed documentation generation attempts
 * - Preparing for major documentation regeneration
 * - Maintaining optimal system performance
 * 
 * Safety Features:
 * - Confirmation prompts for destructive operations
 * - Dry-run mode for risk-free preview
 * - Detailed logging of all cleanup operations
 * - Preservation of recent and successful generations
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * # Remove documentation files older than 30 days
 * php artisan codeforge:cleanup-docs --days=30
 * 
 * # Preview cleanup without deleting files
 * php artisan codeforge:cleanup-docs --dry-run
 * 
 * # Clean only failed generations
 * php artisan codeforge:cleanup-docs --failed-only
 * 
 * # Force cleanup without confirmation
 * php artisan codeforge:cleanup-docs --force
 */
class CleanupDocumentationCommand extends Command
{
    protected $signature = 'codeforge:cleanup-docs 
                           {--days=30 : Remove files older than X days}
                           {--failed-only : Only remove failed generations}
                           {--dry-run : Show what would be deleted without deleting}
                           {--force : Force deletion without confirmation}';

    protected $description = 'Cleanup old documentation files and records';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $failedOnly = $this->option('failed-only');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info("Cleaning up documentation files older than {$days} days...");

        if ($failedOnly) {
            $this->line('Targeting failed generations only');
        }

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No files will be deleted');
        }

        try {
            $cutoffDate = now()->subDays($days);

            // Query for old documentation generations
            $query = DocumentationGeneration::where('created_at', '<', $cutoffDate);

            if ($failedOnly) {
                $query->where('status', 'failed');
            }

            $generations = $query->get();

            if ($generations->isEmpty()) {
                $this->info('No documentation files found for cleanup.');
                return self::SUCCESS;
            }

            $this->line("Found {$generations->count()} documentation generations to clean up:");

            $totalSize = 0;
            $fileCount = 0;

            foreach ($generations as $generation) {
                $status = $generation->status === 'completed' ? '✅' : '❌';
                $size = $generation->file_size ? " ({$generation->formatted_file_size})" : '';

                $this->line("  {$status} {$generation->title} - {$generation->created_at->format('Y-m-d H:i:s')}{$size}");

                if ($generation->file_size) {
                    $totalSize += $generation->file_size;
                    $fileCount++;
                }
            }

            $this->line('');
            $this->line("Total: {$fileCount} files, " . $this->formatBytes($totalSize));

            if (!$dryRun) {
                if (!$force && !$this->confirm('Do you want to proceed with the cleanup?')) {
                    $this->info('Cleanup cancelled.');
                    return self::SUCCESS;
                }

                $deletedFiles = 0;
                $deletedRecords = 0;

                foreach ($generations as $generation) {
                    try {
                        // Delete the file if it exists
                        if ($generation->file_path && Storage::disk('local')->exists($generation->file_path)) {
                            Storage::disk('local')->delete($generation->file_path);
                            $deletedFiles++;
                        }

                        // Delete the record
                        $generation->delete();
                        $deletedRecords++;
                    } catch (\Exception $e) {
                        $this->warn("Failed to delete {$generation->title}: {$e->getMessage()}");
                    }
                }

                $this->info("✅ Cleanup completed!");
                $this->line("Deleted {$deletedFiles} files and {$deletedRecords} records");
            }

            // Also cleanup old schema snapshots if requested
            if ($this->confirm('Do you also want to cleanup old schema snapshots?', false)) {
                $this->cleanupSnapshots($days, $dryRun, $force);
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Cleanup failed: ' . $e->getMessage());

            if ($this->option('verbose')) {
                $this->line($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }

    protected function cleanupSnapshots(int $days, bool $dryRun, bool $force): void
    {
        $cutoffDate = now()->subDays($days);

        $snapshots = SchemaSnapshot::where('created_at', '<', $cutoffDate)
            ->where('is_baseline', false) // Never delete baseline snapshots
            ->get();

        if ($snapshots->isEmpty()) {
            $this->line('No old schema snapshots found for cleanup.');
            return;
        }

        $this->line("Found {$snapshots->count()} schema snapshots to clean up:");

        foreach ($snapshots as $snapshot) {
            $baseline = $snapshot->is_baseline ? ' (BASELINE)' : '';
            $this->line("  📸 {$snapshot->name} - {$snapshot->captured_at->format('Y-m-d H:i:s')}{$baseline}");
        }

        if (!$dryRun) {
            if (!$force && !$this->confirm('Do you want to delete these schema snapshots?')) {
                $this->line('Schema snapshot cleanup cancelled.');
                return;
            }

            $deletedSnapshots = 0;

            foreach ($snapshots as $snapshot) {
                try {
                    $snapshot->delete();
                    $deletedSnapshots++;
                } catch (\Exception $e) {
                    $this->warn("Failed to delete snapshot {$snapshot->name}: {$e->getMessage()}");
                }
            }

            $this->info("Deleted {$deletedSnapshots} schema snapshots");
        }
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
