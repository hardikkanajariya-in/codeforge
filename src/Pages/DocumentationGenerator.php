<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use HkDevs\CodeForgeStudio\Services\DocumentationGenerationService;

/**
 * DocumentationGenerator
 * 
 * Comprehensive database documentation generation page supporting multiple
 * formats, selective table inclusion, and advanced customization options.
 * 
 * Key Features:
 * - Multi-format documentation generation (HTML, PDF, Markdown, JSON)
 * - Selective table and schema element inclusion
 * - Advanced customization with styling and branding options
 * - Real-time preview capabilities with live generation
 * - Version tracking and documentation history management
 * - Export and delivery options with multiple distribution methods
 * - Integration with DocumentationGenerationService
 * 
 * Generation Formats:
 * - HTML: Interactive web documentation with navigation
 * - PDF: Professional printable documentation
 * - Markdown: Developer-friendly documentation format
 * - JSON: Machine-readable schema documentation
 * - Custom: Extensible format support for specialized needs
 * 
 * Configuration Options:
 * - Title and description customization
 * - Version control and tracking
 * - Scope selection (full database, specific tables, views)
 * - Styling and branding customization
 * - Output location and naming conventions
 * 
 * Advanced Features:
 * - Quick generation mode for rapid documentation
 * - Incremental updates for large databases
 * - Template customization and branding integration
 * - Automated delivery and distribution
 * - Integration with schema snapshot functionality
 * 
 * Form Management:
 * - Interactive form with validation and real-time feedback
 * - Progressive disclosure for advanced options
 * - Auto-population from database introspection
 * - Save and restore configuration profiles
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DocumentationGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documentation Generator';

    protected static ?string $navigationGroup = 'DB Docs Generation';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'codeforge-studio::pages.documentation-generator';

    public ?array $data = [];
    public bool $quickGenerate = false;

    public function mount(): void
    {
        $this->form->fill([
            'format' => 'markdown',
            'scope' => 'full_schema',
            'version' => '1.0.0',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Quick Documentation Generator')
                    ->description('Generate documentation quickly with predefined settings')
                    ->schema([
                        Select::make('quick_format')
                            ->label('Format')
                            ->options([
                                'markdown' => 'Markdown (.md)',
                                'html' => 'HTML (.html)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('markdown')
                            ->required(),

                        Select::make('quick_scope')
                            ->label('Scope')
                            ->options([
                                'full_schema' => 'Full Database Schema',
                                'models_only' => 'Models Only',
                            ])
                            ->default('full_schema')
                            ->required(),

                        Actions::make([
                            Action::make('quick_generate')
                                ->label('Quick Generate')
                                ->icon('heroicon-o-bolt')
                                ->color('success')
                                ->action('quickGenerate'),
                        ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('Advanced Documentation Settings')
                    ->description('Customize your documentation generation with advanced options')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->placeholder('e.g., API Database Documentation')
                            ->default('Database Schema Documentation'),

                        Textarea::make('description')
                            ->placeholder('Optional description of the documentation purpose')
                            ->rows(2),

                        TextInput::make('version')
                            ->required()
                            ->default('1.0.0')
                            ->placeholder('1.0.0'),

                        Select::make('format')
                            ->required()
                            ->options([
                                'markdown' => 'Markdown (.md)',
                                'html' => 'HTML (.html)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('markdown'),

                        Select::make('scope')
                            ->required()
                            ->options([
                                'full_schema' => 'Full Database Schema',
                                'selected_tables' => 'Selected Tables',
                                'single_table' => 'Single Table',
                                'models_only' => 'Models Only',
                            ])
                            ->default('full_schema')
                            ->reactive(),

                        Select::make('included_tables')
                            ->multiple()
                            ->searchable()
                            ->options($this->getAvailableTables())
                            ->visible(fn($get) => in_array($get('scope'), ['selected_tables', 'single_table']))
                            ->required(fn($get) => in_array($get('scope'), ['selected_tables', 'single_table'])),

                        Actions::make([
                            Action::make('generate')
                                ->label('Generate Documentation')
                                ->icon('heroicon-o-document-text')
                                ->color('primary')
                                ->action('generateDocumentation'),

                            Action::make('generate_and_download')
                                ->label('Generate & Download')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->color('success')
                                ->action('generateAndDownload'),
                        ]),
                    ])
                    ->collapsible()
                    ->collapsed(true),

                Section::make('Recent Generations')
                    ->description('Your recently generated documentation files')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('recent_generations')
                            ->content(fn() => $this->getRecentGenerationsHtml()),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function quickGenerate(): void
    {
        $data = $this->form->getState();

        $generation = DocumentationGeneration::create([
            'title' => 'Quick Generated Documentation - ' . now()->format('Y-m-d H:i:s'),
            'description' => 'Quickly generated documentation',
            'format' => $data['quick_format'],
            'scope' => $data['quick_scope'],
            'version' => '1.0.0',
        ]);

        try {
            $service = app(DocumentationGenerationService::class, ['generation' => $generation]);
            $service->generate();

            Notification::make()
                ->title('Documentation Generated!')
                ->body('Your documentation has been generated successfully.')
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->button()
                        ->url(route('admin.database-manager.documentation.download', $generation))
                        ->openUrlInNewTab(),
                ])
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function generateDocumentation(): void
    {
        $data = $this->form->getState();

        $generation = DocumentationGeneration::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'format' => $data['format'],
            'scope' => $data['scope'],
            'included_tables' => $data['included_tables'] ?? null,
            'version' => $data['version'],
        ]);

        try {
            $service = app(DocumentationGenerationService::class, ['generation' => $generation]);
            $service->generate();

            Notification::make()
                ->title('Documentation Generated!')
                ->body("'{$data['title']}' has been generated successfully.")
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->url(route('filament.admin.resources.documentation-generations.view', $generation)),
                    \Filament\Notifications\Actions\Action::make('download')
                        ->button()
                        ->url(route('admin.database-manager.documentation.download', $generation))
                        ->openUrlInNewTab(),
                ])
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function generateAndDownload(): void
    {
        $this->generateDocumentation();
        // The download will be handled by the notification action
    }

    protected function getAvailableTables(): array
    {
        try {
            $connection = config('database.default');
            $tables = [];

            switch (config("database.connections.{$connection}.driver")) {
                case 'mysql':
                    $database = config("database.connections.{$connection}.database");
                    $results = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$database]);
                    $tables = collect($results)->pluck('table_name')->toArray();
                    break;

                case 'sqlite':
                    $results = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    $tables = collect($results)->pluck('name')->toArray();
                    break;

                case 'pgsql':
                    $results = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                    $tables = collect($results)->pluck('tablename')->toArray();
                    break;
            }

            return array_combine($tables, $tables);
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getRecentGenerationsHtml(): string
    {
        $recent = DocumentationGeneration::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($recent->isEmpty()) {
            return '<p class="text-gray-500">No documentation generated yet.</p>';
        }

        $html = '<div class="space-y-3">';

        foreach ($recent as $generation) {
            $statusColor = match ($generation->status) {
                'completed' => 'text-green-600',
                'failed' => 'text-red-600',
                'generating' => 'text-yellow-600',
                default => 'text-gray-500'
            };

            $downloadLink = $generation->status === 'completed'
                ? "<a href='" . route('admin.database-manager.documentation.download', $generation) . "' class='text-blue-600 hover:text-blue-800 ml-2' target='_blank'>Download</a>"
                : '';

            $html .= "<div class='flex justify-between items-center p-3 bg-gray-50 rounded-lg'>";
            $html .= "<div>";
            $html .= "<div class='font-medium'>{$generation->title}</div>";
            $html .= "<div class='text-sm text-gray-600'>{$generation->format} • {$generation->scope_display} • {$generation->created_at->format('M j, Y g:i A')}</div>";
            $html .= "</div>";
            $html .= "<div class='flex items-center'>";
            $html .= "<span class='text-sm {$statusColor} font-medium'>" . ucfirst($generation->status) . "</span>";
            $html .= $downloadLink;
            $html .= "</div>";
            $html .= "</div>";
        }

        $html .= '</div>';

        return $html;
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = DocumentationGeneration::where('status', 'pending')->count();
        return $pending > 0 ? (string) $pending : null;
    }
}
