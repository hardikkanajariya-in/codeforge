<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;

/**
 * DatabaseOverview
 *
 * Main dashboard page providing comprehensive overview of database status,
 * available features, and quick access to CodeForge Database Studio tools.
 *
 * Key Features:
 * - Centralized database overview with connection status
 * - Feature availability checking with configuration management
 * - Quick navigation to all CodeForge Database Studio tools
 * - Route validation for enabled features and functionality
 * - Responsive dashboard layout with widget integration
 * - Configuration-driven feature toggling
 *
 * Dashboard Capabilities:
 * - Database connection status and health indicators
 * - Recent activity summary and notifications
 * - Quick access buttons to generator tools
 * - System status indicators and alerts
 * - Feature availability matrix display
 *
 * Feature Management:
 * - Dynamic feature availability checking
 * - Configuration-based feature enabling/disabling
 * - Route existence validation for safety
 * - Graceful handling of disabled features
 *
 * Navigation:
 * - Primary entry point for Database Overview group
 * - Stack icon for database visualization
 * - Top priority sorting for main dashboard prominence
 *
 * Integration Points:
 * - Links to all generator pages and tools
 * - Health monitoring dashboard integration
 * - Schema designer and documentation tools
 * - Smart data seeding and migration management
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class DatabaseOverview extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';

    protected string $view = 'codeforge-studio::pages.database-overview';

    protected static ?string $title = '';

    protected static ?string $navigationLabel = 'Overview';

    protected static ?int $navigationSort = 1;

    public function getHeaderWidgets(): array
    {
        return [];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Database Overview';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    /**
     * Check if a specific feature is enabled and its route exists
     */
    public function isFeatureAvailable(string $configKey, string $routeName): bool
    {
        return config("codeforge-database-studio.features.{$configKey}", true) && Route::has($routeName);
    }

    /**
     * Get header actions for the page
     */
    protected function getHeaderActions(): array
    {
        return [
            // Documentation action moved to Blade view for configuration-based visibility
        ];
    }
}
