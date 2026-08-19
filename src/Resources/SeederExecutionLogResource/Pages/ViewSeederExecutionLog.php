<?php

namespace HkDevs\CodeForgeStudio\Resources\SeederExecutionLogResource\Pages;

use Filament\Infolists\Components;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Resources\SeederExecutionLogResource;

class ViewSeederExecutionLog extends ViewRecord
{
    protected static string $resource = SeederExecutionLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\Section::make('Execution Details')
                    ->schema([
                        Components\TextEntry::make('seeder_name')
                            ->label('Seeder Name'),

                        Components\TextEntry::make('seeder_class')
                            ->label('Seeder Class'),

                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'completed' => 'success',
                                'failed' => 'danger',
                                'started' => 'warning',
                                default => 'gray',
                            }),

                        Components\TextEntry::make('executed_by')
                            ->label('Executed By'),
                    ])
                    ->columns(2),

                Components\Section::make('Statistics')
                    ->schema([
                        Components\TextEntry::make('records_created')
                            ->label('Records Created'),

                        Components\TextEntry::make('records_updated')
                            ->label('Records Updated'),

                        Components\TextEntry::make('records_failed')
                            ->label('Records Failed'),

                        Components\TextEntry::make('execution_time')
                            ->label('Execution Time')
                            ->suffix(' seconds'),
                    ])
                    ->columns(2),

                Components\Section::make('Timeline')
                    ->schema([
                        Components\TextEntry::make('started_at')
                            ->dateTime(),

                        Components\TextEntry::make('completed_at')
                            ->dateTime(),

                        Components\TextEntry::make('duration')
                            ->label('Duration')
                            ->getStateUsing(function ($record) {
                                return $record->duration;
                            }),
                    ])
                    ->columns(3),

                Components\Section::make('Output')
                    ->schema([
                        Components\TextEntry::make('output')
                            ->columnSpanFull()
                            ->markdown()
                            ->placeholder('No output captured'),
                    ])
                    ->collapsible()
                    ->visible(fn ($record) => ! empty($record->output)),

                Components\Section::make('Error Details')
                    ->schema([
                        Components\TextEntry::make('error_message')
                            ->columnSpanFull()
                            ->markdown()
                            ->color('danger'),
                    ])
                    ->collapsible()
                    ->visible(fn ($record) => ! empty($record->error_message)),

                Components\Section::make('Metadata')
                    ->schema([
                        Components\KeyValueEntry::make('metadata')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn ($record) => ! empty($record->metadata)),
            ]);
    }
}
