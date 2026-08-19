<?php

namespace HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Models\SchemaSnapshot;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;
use HkDevs\CodeForgeStudio\Support\Section;

/**
 * CompareSchemaSnapshots
 *
 * Specialized Filament page for comparing database schema snapshots with
 * detailed difference analysis and visualization capabilities.
 *
 * Key Features:
 * - Side-by-side schema snapshot comparison
 * - Detailed difference highlighting and analysis
 * - Visual representation of schema changes
 * - Migration script generation from differences
 * - Change impact analysis and recommendations
 *
 * Comparison Capabilities:
 * - Table structure comparison with field-level differences
 * - Index and constraint comparison analysis
 * - Relationship mapping and foreign key changes
 * - Data type and attribute difference detection
 * - Schema evolution tracking and visualization
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class CompareSchemaSnapshots extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SchemaSnapshotResource::class;

    protected string $view = 'codeforge-studio::pages.compare-schema-snapshots';

    public SchemaSnapshot $record;

    public ?SchemaSnapshot $compareWith = null;

    public ?array $comparison = null;

    public ?int $compare_with_id = null;

    public function mount(SchemaSnapshot $record): void
    {
        $this->record = $record;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Compare Snapshots')
                ->description('Select another snapshot to compare with the current one')
                ->schema([
                    Select::make('compare_with_id')
                        ->label('Compare with')
                        ->options(function () {
                            return SchemaSnapshot::where('id', '!=', $this->record->id)
                                ->where('database_connection', $this->record->database_connection)
                                ->orderBy('captured_at', 'desc')
                                ->get()
                                ->mapWithKeys(function ($snapshot) {
                                    return [$snapshot->id => "{$snapshot->name} ({$snapshot->captured_at->format('Y-m-d H:i')})"];
                                });
                        })
                        ->searchable()
                        ->live(debounce: 300)
                        ->afterStateUpdated(function ($state) {
                            $this->compare_with_id = $state;
                            if ($state) {
                                $this->compareWith = SchemaSnapshot::find($state);
                                $this->generateComparison();
                            } else {
                                $this->compareWith = null;
                                $this->comparison = null;
                            }
                        }),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Snapshot')
                ->url(fn () => SchemaSnapshotResource::getUrl('view', ['record' => $this->record])),

            Action::make('export_comparison')
                ->label('Export Comparison')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn () => $this->comparison !== null)
                ->action(function () {
                    $this->exportComparison();
                }),
        ];
    }

    protected function generateComparison(): void
    {
        if (! $this->compareWith) {
            return;
        }

        $this->comparison = [
            'from' => [
                'name' => $this->compareWith->name,
                'captured_at' => $this->compareWith->captured_at,
                'tables_count' => $this->compareWith->tables_count,
            ],
            'to' => [
                'name' => $this->record->name,
                'captured_at' => $this->record->captured_at,
                'tables_count' => $this->record->tables_count,
            ],
            'changes' => $this->record->compareSchemas(
                $this->compareWith->schema_data ?? [],
                $this->record->schema_data ?? []
            ),
        ];
    }

    protected function exportComparison(): void
    {
        if (! $this->comparison) {
            return;
        }

        $markdown = $this->generateComparisonMarkdown();

        // Sanitize the filename by removing invalid characters
        $fromName = preg_replace('/[^\w\-_\.]/', '_', $this->compareWith->name);
        $toName = preg_replace('/[^\w\-_\.]/', '_', $this->record->name);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "schema-comparison-{$fromName}-to-{$toName}-{$timestamp}.md";

        // Ensure temp directory exists with proper permissions
        $tempDir = storage_path('app'.DIRECTORY_SEPARATOR.'temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $path = $tempDir.DIRECTORY_SEPARATOR.$filename;

        try {
            file_put_contents($path, $markdown);

            Notification::make()
                ->title('Comparison Exported')
                ->body("Comparison saved as {$filename} in storage/app/temp/")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Failed')
                ->body('Failed to export comparison: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function generateComparisonMarkdown(): string
    {
        $changes = $this->comparison['changes'];
        $from = $this->comparison['from'];
        $to = $this->comparison['to'];

        $markdown = "# Schema Comparison Report\n\n";
        $markdown .= "**From:** {$from['name']} ({$from['captured_at']->format('Y-m-d H:i:s')})\n";
        $markdown .= "**To:** {$to['name']} ({$to['captured_at']->format('Y-m-d H:i:s')})\n";
        $markdown .= '**Generated:** '.now()->format('Y-m-d H:i:s')."\n\n";

        $markdown .= "## Summary\n\n";
        $markdown .= '- **Added Tables:** '.count($changes['added_tables'] ?? [])."\n";
        $markdown .= '- **Removed Tables:** '.count($changes['removed_tables'] ?? [])."\n";
        $markdown .= '- **Modified Tables:** '.count($changes['modified_tables'] ?? [])."\n\n";

        if (! empty($changes['added_tables'])) {
            $markdown .= "## Added Tables\n\n";
            foreach ($changes['added_tables'] as $table) {
                $markdown .= "- `{$table}`\n";
            }
            $markdown .= "\n";
        }

        if (! empty($changes['removed_tables'])) {
            $markdown .= "## Removed Tables\n\n";
            foreach ($changes['removed_tables'] as $table) {
                $markdown .= "- `{$table}`\n";
            }
            $markdown .= "\n";
        }

        if (! empty($changes['modified_tables'])) {
            $markdown .= "## Modified Tables\n\n";
            foreach ($changes['modified_tables'] as $tableChange) {
                $markdown .= "### `{$tableChange['table']}`\n\n";

                $tableChanges = $tableChange['changes'];

                if (! empty($tableChanges['added_columns'])) {
                    $markdown .= "**Added Columns:**\n";
                    foreach ($tableChanges['added_columns'] as $column) {
                        $markdown .= "- `{$column}`\n";
                    }
                    $markdown .= "\n";
                }

                if (! empty($tableChanges['removed_columns'])) {
                    $markdown .= "**Removed Columns:**\n";
                    foreach ($tableChanges['removed_columns'] as $column) {
                        $markdown .= "- `{$column}`\n";
                    }
                    $markdown .= "\n";
                }

                if (! empty($tableChanges['modified_columns'])) {
                    $markdown .= "**Modified Columns:**\n";
                    foreach ($tableChanges['modified_columns'] as $column => $change) {
                        $markdown .= "- `{$column}`: Changed\n";
                    }
                    $markdown .= "\n";
                }
            }
        }

        return $markdown;
    }

    public function getTitle(): string
    {
        return 'Compare Schema Snapshots';
    }
}
