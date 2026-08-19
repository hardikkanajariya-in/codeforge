<?php

namespace HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource\Pages;
use HkDevs\CodeForgeStudio\Support\Section;
use Filament\Schemas\Schema;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource;


use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use HkDevs\CodeForgeStudio\Services\DocumentationGenerationService;
use Filament\Notifications\Notification;

class ViewDocumentationGeneration extends ViewRecord
{
    protected static string $resource = DocumentationGenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('generate')
                ->label('Generate')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->visible(fn() => $this->record->status === 'pending')
                ->action(function () {
                    try {
                        $service = app(DocumentationGenerationService::class, ['generation' => $this->record]);
                        $service->generate();

                        Notification::make()
                            ->title('Documentation Generated')
                            ->success()
                            ->send();

                        $this->refreshFormData(['status', 'file_path', 'file_size', 'generated_at']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Generation Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('download')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn() => $this->record->status === 'completed')
                ->url(fn() => route('admin.database-manager.documentation.download', $this->record))
                ->openUrlInNewTab(),

            Actions\Action::make('regenerate')
                ->label('Regenerate')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn() => in_array($this->record->status, ['completed', 'failed']))
                ->action(function () {
                    $this->record->update(['status' => 'pending']);

                    Notification::make()
                        ->title('Marked for Regeneration')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('title')
                            ->weight('medium'),
                        TextEntry::make('description')
                            ->placeholder('No description provided'),
                        TextEntry::make('version'),
                    ])->columns(3),

                Section::make('Generation Settings')
                    ->schema([
                        TextEntry::make('format')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'markdown' => 'info',
                                'html' => 'success',
                                'pdf' => 'warning',
                                default => 'gray'
                            }),
                        TextEntry::make('scope')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'full_schema' => 'Full Database Schema',
                                'selected_tables' => 'Selected Tables',
                                'single_table' => 'Single Table',
                                'models_only' => 'Models Only',
                                default => $state
                            })
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'full_schema' => 'primary',
                                'selected_tables' => 'success',
                                'single_table' => 'warning',
                                'models_only' => 'info',
                                default => 'gray'
                            }),
                        TextEntry::make('included_tables')
                            ->listWithLineBreaks()
                            ->visible(fn() => !empty($this->record->included_tables)),
                    ])->columns(2),

                Section::make('Generation Status')
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'pending' => 'gray',
                                'generating' => 'warning',
                                'completed' => 'success',
                                'failed' => 'danger',
                                default => 'gray'
                            }),
                        TextEntry::make('formatted_file_size')
                            ->label('File Size')
                            ->visible(fn() => $this->record->file_size !== null),
                        TextEntry::make('generated_at')
                            ->dateTime()
                            ->visible(fn() => $this->record->generated_at !== null),
                        TextEntry::make('generated_by')
                            ->visible(fn() => $this->record->generated_by !== null),
                        TextEntry::make('error_message')
                            ->visible(fn() => $this->record->status === 'failed')
                            ->color('danger'),
                    ])->columns(2),

                Section::make('Generation Metadata')
                    ->schema([
                        KeyValueEntry::make('metadata')
                            ->visible(fn() => !empty($this->record->metadata)),
                    ])
                    ->visible(fn() => !empty($this->record->metadata))
                    ->collapsible(),

                Section::make('Advanced Options')
                    ->schema([
                        KeyValueEntry::make('options')
                            ->visible(fn() => !empty($this->record->options)),
                    ])
                    ->visible(fn() => !empty($this->record->options))
                    ->collapsible(),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
