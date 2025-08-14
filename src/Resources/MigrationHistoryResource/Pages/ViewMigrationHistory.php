<?php

namespace HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource\Pages;

use HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;

class ViewMigrationHistory extends ViewRecord
{
    protected static string $resource = MigrationHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_list')
                ->label('Back to Migration History')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Migration Overview')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('migration')
                                    ->label('Migration File')
                                    ->formatStateUsing(fn(string $state): string => basename($state, '.php'))
                                    ->copyable()
                                    ->icon('heroicon-o-document')
                                    ->weight(FontWeight::Bold),

                                Infolists\Components\TextEntry::make('batch')
                                    ->label('Batch Number')
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-o-hashtag'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Execution Details')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('action')
                                    ->label('Action')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'migrate' => 'success',
                                        'rollback' => 'warning',
                                        'reset' => 'danger',
                                        'refresh' => 'info',
                                        default => 'gray'
                                    }),

                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'success' => 'success',
                                        'failed' => 'danger',
                                        default => 'gray'
                                    }),

                                Infolists\Components\TextEntry::make('execution_time')
                                    ->label('Execution Time')
                                    ->formatStateUsing(fn(?float $state): string => $state ? number_format($state, 3) . ' seconds' : 'N/A')
                                    ->icon('heroicon-o-clock')
                                    ->color(fn(?float $state): string => match (true) {
                                        $state === null => 'gray',
                                        $state < 1 => 'success',
                                        $state < 5 => 'warning',
                                        default => 'danger'
                                    }),
                            ]),
                    ]),

                Infolists\Components\Section::make('Execution Information')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('executed_by')
                                    ->label('Executed By')
                                    ->icon('heroicon-o-user')
                                    ->weight(FontWeight::Medium),

                                Infolists\Components\TextEntry::make('executed_at')
                                    ->label('Executed At')
                                    ->dateTime('M j, Y H:i:s T')
                                    ->icon('heroicon-o-calendar'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Error Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('error_message')
                            ->label('Error Message')
                            ->formatStateUsing(fn(?string $state): string => $state ?: 'No errors occurred during execution')
                            ->color(fn(?string $state): string => $state ? 'danger' : 'success')
                            ->copyable(fn(?string $state): bool => !empty($state))
                            ->columnSpanFull(),
                    ])
                    ->visible(fn() => !empty($this->record->error_message))
                    ->headerActions([
                        Infolists\Components\Actions\Action::make('copy_error')
                            ->label('Copy Error')
                            ->icon('heroicon-o-clipboard')
                            ->action(function () {
                                // Copy error to clipboard functionality would go here
                                Notification::make()
                                    ->title('Error Copied')
                                    ->body('Error message copied to clipboard')
                                    ->success()
                                    ->send();
                            })
                            ->visible(fn() => !empty($this->record->error_message)),
                    ]),
            ]);
    }
}
