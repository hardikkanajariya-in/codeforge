<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\MigrationTrackingService;
use Illuminate\Console\Command;

/**
 * SyncMigrationHistoryCommand
 *
 * Database migration history synchronization utility for CodeForge Database Studio.
 * Ensures consistency between Laravel's migrations table and CodeForge's enhanced tracking system.
 *
 * Features:
 * - Intelligent migration history synchronization
 * - Orphaned entry detection and cleanup
 * - Missing migration discovery and registration
 * - Data integrity validation and repair
 * - Comprehensive reporting of sync operations
 * - Safe cleanup of inconsistent records
 *
 * Synchronization Operations:
 * - Missing Migration Sync: Register untracked migrations in CodeForge history
 * - Orphaned Entry Cleanup: Remove history records for non-existent migrations
 * - Status Reconciliation: Align migration status between systems
 * - Metadata Reconstruction: Rebuild missing migration metadata
 *
 * Data Integrity Features:
 * - Cross-reference validation between migration systems
 * - Automatic detection of migration table inconsistencies
 * - Safe handling of corrupted or incomplete migration records
 * - Preservation of critical migration history data
 *
 * Cleanup Capabilities:
 * - Optional cleanup of orphaned migration history entries
 * - Removal of duplicate or conflicting records
 * - Normalization of migration metadata
 * - Optimization of migration history table structure
 *
 * Safety Measures:
 * - Non-destructive synchronization by default
 * - Optional cleanup mode with explicit user consent
 * - Detailed reporting of all changes made
 * - Validation of sync operations before execution
 *
 * Use Cases:
 * - Post-installation migration history initialization
 * - Recovery from migration system corruption
 * - Migration history maintenance and optimization
 * - Troubleshooting migration tracking issues
 * - Database migration audit and compliance
 *
 * Maintenance Benefits:
 * - Improved migration tracking accuracy
 * - Enhanced migration rollback reliability
 * - Better migration dependency resolution
 * - Optimized migration history performance
 * - Consistent cross-environment migration tracking
 *
 * Integration Features:
 * - Compatible with all Laravel migration versions
 * - Supports custom migration paths and structures
 * - Integration with CodeForge monitoring systems
 * - Automated execution support for maintenance schedules
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Sync migration history
 * php artisan codeforge:sync-migration-history
 *
 * # Sync with cleanup of orphaned entries
 * php artisan codeforge:sync-migration-history --cleanup
 *
 * # Use in maintenance schedules
 * $schedule->command('codeforge:sync-migration-history --cleanup')->daily();
 */
class SyncMigrationHistoryCommand extends Command
{
    protected $signature = 'codeforge:sync-migration-history {--cleanup : Also cleanup orphaned entries}';

    protected $description = 'Sync migration history with the migrations table';

    public function handle(MigrationTrackingService $service)
    {
        $this->info('Syncing migration history...');

        // Sync missing migrations
        $service->syncMigrationHistory();
        $this->info('✓ Migration history synced');

        // Cleanup orphaned entries if requested
        if ($this->option('cleanup')) {
            $deleted = $service->cleanupOrphanedEntries();
            $this->info("✓ Cleaned up {$deleted} orphaned entries");
        }

        $this->info('Migration history sync completed!');
    }
}
