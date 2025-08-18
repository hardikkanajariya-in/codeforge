<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

/**
 * GeneratorOverviewPage
 * 
 * Central hub page providing overview and quick access to all CodeForge
 * Database Studio code generation tools and capabilities.
 * 
 * Key Features:
 * - Comprehensive overview of all available generators
 * - Quick access navigation to specific generator tools
 * - Generator status and availability checking
 * - Recent generation history and activity tracking
 * - Performance metrics and usage analytics
 * - Template management and customization options
 * 
 * Generator Categories:
 * - Model Generator: Laravel Eloquent model creation
 * - Migration Generator: Database migration file generation
 * - Factory Generator: Model factory creation with realistic data
 * - Seeder Generator: Database seeder generation and management
 * - Filament Resource Generator: Complete admin resource creation
 * - Documentation Generator: Database documentation automation
 * 
 * Dashboard Features:
 * - Generator availability status indicators
 * - Recent activity timeline and history
 * - Quick action buttons for common tasks
 * - Performance metrics and generation statistics
 * - Configuration management and settings access
 * 
 * Navigation Hub:
 * - Direct links to all generator pages
 * - Context-aware generator recommendations
 * - Workflow guidance for complex generation tasks
 * - Help and documentation integration
 * 
 * Page Layout:
 * - Full-width layout for comprehensive dashboard
 * - Card-based interface for generator organization
 * - Responsive design for optimal usability
 * - Interactive elements for enhanced user experience
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class GeneratorOverviewPage extends Page
{
    protected static string $view = 'codeforge-studio::pages.generator-overview';
    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?string $title = 'Code Generator Overview';
    protected static ?string $navigationLabel = 'Code Generators';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationGroup = 'Code Generators';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('migration_generator')
                ->label('Migration Generator')
                ->icon('heroicon-o-table-cells')
                ->color('info')
                ->url(MigrationGeneratorPage::getUrl()),

            Action::make('model_generator')
                ->label('Model Generator')
                ->icon('heroicon-o-cube')
                ->color('success')
                ->url(ModelGeneratorPage::getUrl()),

            Action::make('factory_generator')
                ->label('Factory Generator')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('warning')
                ->url(FactoryGeneratorPage::getUrl()),

            Action::make('seeder_generator')
                ->label('Seeder Generator')
                ->icon('heroicon-o-circle-stack')
                ->color('danger')
                ->url(SeederGeneratorPage::getUrl()),

            Action::make('resource_generator')
                ->label('Filament Resource')
                ->icon('heroicon-o-squares-2x2')
                ->color('gray')
                ->url(FilamentResourceGeneratorPage::getUrl()),
        ];
    }

    public function getGeneratorStats(): array
    {
        return [
            [
                'name' => 'Migration Generator',
                'description' => 'Generate database migration files with advanced column types, indexes, and foreign keys.',
                'icon' => 'heroicon-o-table-cells',
                'color' => 'info',
                'url' => MigrationGeneratorPage::getUrl(),
                'features' => [
                    'Advanced column types',
                    'Indexes and constraints',
                    'Foreign key relationships',
                    'UUID primary keys',
                    'Auto-suggestions based on table name',
                ],
            ],
            [
                'name' => 'Model Generator',
                'description' => 'Create Eloquent models with relationships, casts, scopes, and custom methods.',
                'icon' => 'heroicon-o-cube',
                'color' => 'success',
                'url' => ModelGeneratorPage::getUrl(),
                'features' => [
                    'Relationship definitions',
                    'Attribute casting',
                    'Query scopes',
                    'Mutators and accessors',
                    'Custom methods',
                ],
            ],
            [
                'name' => 'Factory Generator',
                'description' => 'Build model factories with fake data, states, sequences, and callbacks.',
                'icon' => 'heroicon-o-cog-6-tooth',
                'color' => 'warning',
                'url' => FactoryGeneratorPage::getUrl(),
                'features' => [
                    'Faker data providers',
                    'Factory states',
                    'Sequences',
                    'After creating/making callbacks',
                    'Multiple locales support',
                ],
            ],
            [
                'name' => 'Seeder Generator',
                'description' => 'Generate database seeders with factory integration and manual data insertion.',
                'icon' => 'heroicon-o-circle-stack',
                'color' => 'danger',
                'url' => SeederGeneratorPage::getUrl(),
                'features' => [
                    'Factory integration',
                    'Manual data insertion',
                    'Environment-specific seeding',
                    'Chunked processing',
                    'Foreign key handling',
                ],
            ],
            [
                'name' => 'Filament Resource Generator',
                'description' => 'Create complete Filament resources with forms, tables, filters, and actions.',
                'icon' => 'heroicon-o-squares-2x2',
                'color' => 'gray',
                'url' => FilamentResourceGeneratorPage::getUrl(),
                'features' => [
                    'Table columns configuration',
                    'Form field definitions',
                    'Filters and actions',
                    'Bulk actions',
                    'Global search integration',
                ],
            ],
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
