<?php

namespace HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource;

/**
 * ListDatabaseHealthMetrics
 *
 * Filament list page for displaying and managing database health metrics
 * with comprehensive filtering, search, and bulk operation capabilities.
 *
 * Key Features:
 * - Comprehensive health metrics listing with real-time updates
 * - Advanced filtering by connection, metric type, and status
 * - Bulk operations for metric management and cleanup
 * - Export capabilities for reporting and analysis
 * - Integration with health monitoring dashboard
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class ListDatabaseHealthMetrics extends ListRecords
{
    protected static string $resource = DatabaseHealthMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add any specific actions if needed
        ];
    }
}
