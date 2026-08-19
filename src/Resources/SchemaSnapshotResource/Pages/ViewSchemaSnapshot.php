<?php

namespace HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;

class ViewSchemaSnapshot extends ViewRecord
{
    protected static string $resource = SchemaSnapshotResource::class;

    protected function getFormSchema(): array
    {
        // Prevent any automatic form field generation
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Remove array fields that might cause htmlspecialchars errors
        return array_diff_key($data, array_flip([
            'schema_data',
            'table_relationships',
            'model_mappings',
            'validation_rules',
            'policy_information',
        ]));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('create_documentation')
                ->label('Create Documentation')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(fn () => route('filament.admin.resources.documentation-generations.create', [
                    'snapshot_id' => $this->record->id,
                ])),

            Actions\Action::make('mark_baseline')
                ->label('Mark as Baseline')
                ->icon('heroicon-o-star')
                ->color('warning')
                ->visible(fn () => ! $this->record->is_baseline)
                ->action(function () {
                    $this->record->markAsBaseline();
                    $this->record->refresh();
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Snapshot Information')
                    ->schema([
                        TextEntry::make('name')
                            ->weight('medium'),
                        TextEntry::make('description')
                            ->placeholder('No description provided'),
                        TextEntry::make('version'),
                        TextEntry::make('database_connection')
                            ->badge(),
                        IconEntry::make('is_baseline')
                            ->boolean()
                            ->trueIcon('heroicon-o-star')
                            ->falseIcon('heroicon-o-minus')
                            ->trueColor('warning')
                            ->falseColor('gray'),
                    ])->columns(3),

                Section::make('Schema Statistics')
                    ->schema([
                        TextEntry::make('tables_count')
                            ->label('Tables')
                            ->numeric(),
                        TextEntry::make('relationships_count')
                            ->label('Relationships')
                            ->numeric(),
                        TextEntry::make('models_count')
                            ->label('Models')
                            ->numeric(),
                        TextEntry::make('hash')
                            ->label('Schema Hash')
                            ->copyable()
                            ->copyMessage('Schema hash copied')
                            ->copyMessageDuration(1500),
                    ])->columns(4),

                Section::make('Tables Overview')
                    ->schema([
                        RepeatableEntry::make('tables_with_relationships')
                            ->label('')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Table'),
                                        TextEntry::make('columns_count')
                                            ->label('Columns')
                                            ->numeric(),
                                        TextEntry::make('relationships_count')
                                            ->label('Relationships')
                                            ->numeric(),
                                        TextEntry::make('model_class')
                                            ->label('Model')
                                            ->placeholder('No model')
                                            ->badge()
                                            ->color('info')
                                            ->formatStateUsing(function ($state) {
                                                if (is_null($state) || empty($state)) {
                                                    return 'No model';
                                                }

                                                if (is_string($state) && str_contains($state, '\\')) {
                                                    return class_basename($state);
                                                }

                                                return is_string($state) ? $state : 'Unknown';
                                            }),
                                    ]),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Capture Information')
                    ->schema([
                        TextEntry::make('captured_at')
                            ->dateTime(),
                        TextEntry::make('captured_by'),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
