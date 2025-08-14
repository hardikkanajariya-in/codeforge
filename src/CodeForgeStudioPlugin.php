<?php

namespace HkDevs\CodeForgeStudio;

use Filament\Contracts\Plugin;
use Filament\Panel;
use HkDevs\CodeForgeStudio\Pages\DatabaseOverview;
use HkDevs\CodeForgeStudio\Pages\DatabaseHealthDashboard;
use HkDevs\CodeForgeStudio\Pages\SchemaDesigner;
use HkDevs\CodeForgeStudio\Pages\SmartDataSeeder;
use HkDevs\CodeForgeStudio\Pages\DocumentationGenerator;
use HkDevs\CodeForgeStudio\Pages\MigrationManager;
use HkDevs\CodeForgeStudio\Pages\GeneratorOverviewPage;
use HkDevs\CodeForgeStudio\Pages\MigrationGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\ModelGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\FactoryGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\SeederGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\FilamentResourceGeneratorPage;
use HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource;
use HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource;
use HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource;
use HkDevs\CodeForgeStudio\Resources\DataSeederResource;
use HkDevs\CodeForgeStudio\Resources\SeederExecutionLogResource;
use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;

class CodeForgeStudioPlugin implements Plugin
{
    protected bool $enableSchemaDesigner = true;
    protected bool $enableMigrationManager = true;
    protected bool $enableHealthMonitoring = true;
    protected bool $enableSmartSeeding = true;
    protected bool $enableDocumentationGenerator = true;
    protected bool $enableCodeGeneration = true;
    protected bool $enableDevDocs = false;
    protected ?string $navigationGroup = null;
    protected ?int $navigationSort = null;

    public function getId(): string
    {
        return 'codeforge-database-studio';
    }

    public function register(Panel $panel): void
    {
        $pages = [];
        $resources = [];

        // Store plugin configuration in app container for Blade views
        app()->singleton('codeforge-plugin-config', function () {
            return [
                'enable_schema_designer' => $this->enableSchemaDesigner,
                'enable_migration_manager' => $this->enableMigrationManager,
                'enable_health_monitoring' => $this->enableHealthMonitoring,
                'enable_smart_seeding' => $this->enableSmartSeeding,
                'enable_documentation_generator' => $this->enableDocumentationGenerator,
                'enable_code_generation' => $this->enableCodeGeneration,
                'enable_dev_docs' => $this->enableDevDocs,
            ];
        });

        // Always include the overview page (Phase 1)
        $pages[] = DatabaseOverview::class;

        // Phase 2: Schema Designer
        if ($this->enableSchemaDesigner) {
            $pages[] = SchemaDesigner::class;
        }

        // Phase 3: Migration Manager
        if ($this->enableMigrationManager) {
            $pages[] = MigrationManager::class;
            $resources[] = MigrationHistoryResource::class;
        }

        // Phase 4: Health Monitoring
        if ($this->enableHealthMonitoring) {
            $pages[] = DatabaseHealthDashboard::class;
            $resources[] = QueryPerformanceLogResource::class;
            $resources[] = DatabaseHealthMetricResource::class;
        }

        // Phase 5: Smart Seeding
        if ($this->enableSmartSeeding) {
            $pages[] = SmartDataSeeder::class;
            $resources[] = DataSeederResource::class;
            $resources[] = SeederExecutionLogResource::class;
            $resources[] = DataGenerationTemplateResource::class;
        }

        // Phase 6: Documentation Generator
        if ($this->enableDocumentationGenerator) {
            $pages[] = DocumentationGenerator::class;
            $resources[] = DocumentationGenerationResource::class;
            $resources[] = SchemaSnapshotResource::class;
        }

        // Phase 7: Code Generation (Separate from Migration Manager)
        if ($this->enableCodeGeneration) {
            $pages[] = GeneratorOverviewPage::class;
            $pages[] = MigrationGeneratorPage::class;
            $pages[] = ModelGeneratorPage::class;
            $pages[] = FactoryGeneratorPage::class;
            $pages[] = SeederGeneratorPage::class;
            $pages[] = FilamentResourceGeneratorPage::class;
        }

        $panel
            ->pages($pages)
            ->resources($resources);
    }

    public function boot(Panel $panel): void
    {
        // Plugin boot logic
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function enableSchemaDesigner(bool $enable = true): static
    {
        $this->enableSchemaDesigner = $enable;
        return $this;
    }

    public function enableMigrationManager(bool $enable = true): static
    {
        $this->enableMigrationManager = $enable;
        return $this;
    }

    public function enableHealthMonitoring(bool $enable = true): static
    {
        $this->enableHealthMonitoring = $enable;
        return $this;
    }

    public function enableSmartSeeding(bool $enable = true): static
    {
        $this->enableSmartSeeding = $enable;
        return $this;
    }

    public function enableDocumentationGenerator(bool $enable = true): static
    {
        $this->enableDocumentationGenerator = $enable;
        return $this;
    }

    public function enableCodeGeneration(bool $enable = true): static
    {
        $this->enableCodeGeneration = $enable;
        return $this;
    }

    public function enableDevDocs(bool $enable = true): static
    {
        $this->enableDevDocs = $enable;
        return $this;
    }

    public function navigationGroup(?string $group = null): static
    {
        $this->navigationGroup = $group;
        return $this;
    }

    public function navigationSort(?int $sort = null): static
    {
        $this->navigationSort = $sort;
        return $this;
    }
}
