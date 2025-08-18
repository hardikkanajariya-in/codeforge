<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Stats (Filament grid + sections) -->
        <x-filament::grid default="1" md="3" class="gap-6">
            <x-filament::section :heading="__('Executed')">
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold">{{ $executedCount }}</div>
                    <x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6 text-green-600" />
                </div>
            </x-filament::section>

            <x-filament::section :heading="__('Pending')">
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold">{{ $pendingCount }}</div>
                    <x-filament::icon icon="heroicon-o-clock" class="h-6 w-6 text-yellow-600" />
                </div>
            </x-filament::section>

            <x-filament::section :heading="__('Total')">
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold">{{ $totalCount }}</div>
                    <x-filament::icon icon="heroicon-o-rectangle-stack" class="h-6 w-6 text-blue-600" />
                </div>
            </x-filament::section>
        </x-filament::grid>

        <!-- Migrations Table in Filament section -->
        <x-filament::section :heading="__('Database Migrations')" :description="__('Manage your database migrations with individual control over each migration file.')">

            @if(empty($migrations))
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No migrations found</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create your first migration to get started with
                        database management.</p>
                </div>
            @else
                @php
                    // Sort migrations: pending first, then executed (by batch desc)
                    $sortedMigrations = collect($migrations)->sort(function ($a, $b) {
                        if ($a['status'] !== $b['status']) {
                            return $a['status'] === 'pending' ? -1 : 1;
                        }
                        if ($a['status'] === 'executed') {
                            return ($b['batch'] ?? 0) <=> ($a['batch'] ?? 0);
                        }
                        return strcmp($b['migration'], $a['migration']);
                    });
                @endphp

                <div class="fi-ta-table overflow-x-auto">
                    <table class="fi-ta-table-content w-full table-auto divide-y divide-gray-200 text-start dark:divide-gray-700">
                        <thead class="fi-ta-header">
                            <tr class="bg-gray-50 dark:bg-gray-900">
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Migration
                                        </span>
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Status
                                        </span>
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Batch
                                        </span>
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Executed At
                                        </span>
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Actions
                                        </span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-gray-700">
                            @foreach($sortedMigrations as $migration)
                                <tr class="fi-ta-row transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5" wire:key="migration-{{ $migration['migration'] }}">
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            <div class="flex flex-col space-y-2">
                                                <div class="fi-ta-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white font-medium">
                                                    {{ $migration['display_name'] }}
                                                </div>
                                                <x-filament::badge color="gray" class="font-mono self-start">
                                                    {{ $migration['migration'] }}
                                                </x-filament::badge>
                                                @if(!$migration['file_exists'])
                                                    <x-filament::badge color="danger" class="self-start">
                                                        Migration file not found
                                                    </x-filament::badge>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            @if($migration['status'] === 'executed')
                                                <x-filament::badge color="success" icon="heroicon-o-check-circle">
                                                    Executed
                                                </x-filament::badge>
                                            @elseif($migration['status'] === 'pending')
                                                <x-filament::badge color="warning" icon="heroicon-o-clock">
                                                    Pending
                                                </x-filament::badge>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            @if($migration['batch'])
                                                <x-filament::badge color="gray">#{{ $migration['batch'] }}</x-filament::badge>
                                            @else
                                                <span class="fi-ta-text-item text-sm leading-6 text-gray-950 dark:text-white">—</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            <span class="fi-ta-text-item text-sm leading-6 text-gray-950 dark:text-white">
                                                {{ $migration['executed_at'] ? $migration['executed_at']->format('M j, Y H:i') : '—' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            <div class="flex items-center gap-2">
                                                @if($migration['status'] === 'pending' && $migration['file_exists'])
                                                    <x-filament::button color="success" icon="heroicon-o-play"
                                                        x-on:click="$dispatch('open-modal', { 
                                                            id: 'confirm-run-{{ $migration['migration'] }}' 
                                                        })"
                                                        size="xs">
                                                        Run
                                                    </x-filament::button>

                                                    <x-filament::modal id="confirm-run-{{ $migration['migration'] }}" width="md">
                                                        <x-slot name="heading">
                                                            Run Migration
                                                        </x-slot>

                                                        <div class="fi-mo-content">
                                                            <p class="fi-modal-description">
                                                                Are you sure you want to run the migration <br/> <strong>{{ $migration['display_name'] }}</strong>?
                                                            </p>
                                                        </div>

                                                        <x-slot name="footerActions">
                                                            <x-filament::button color="gray" x-on:click="close">
                                                                Cancel
                                                            </x-filament::button>

                                                            <x-filament::button
                                                                color="success"
                                                                wire:click="runMigration('{{ $migration['migration'] }}')"
                                                                wire:loading.attr="disabled"
                                                                wire:target="runMigration('{{ $migration['migration'] }}')"
                                                                x-on:click="close">
                                                                <span wire:loading.remove wire:target="runMigration('{{ $migration['migration'] }}')">
                                                                    Confirm
                                                                </span>
                                                                <span wire:loading wire:target="runMigration('{{ $migration['migration'] }}')">
                                                                    Running...
                                                                </span>
                                                            </x-filament::button>
                                                        </x-slot>
                                                    </x-filament::modal>
                                                @elseif($migration['status'] === 'executed' && $migration['file_exists'])
                                                    @if($migration['can_rollback_individually'])
                                                        <x-filament::button color="danger" icon="heroicon-o-arrow-uturn-left"
                                                            x-on:click="$dispatch('open-modal', { 
                                                                id: 'confirm-rollback-{{ $migration['migration'] }}' 
                                                            })"
                                                            size="xs">
                                                            Rollback
                                                        </x-filament::button>

                                                        <x-filament::modal id="confirm-rollback-{{ $migration['migration'] }}" width="md">
                                                            <x-slot name="heading">
                                                                Confirm Migration Rollback
                                                            </x-slot>

                                                            <div class="fi-mo-content">
                                                                <p class="fi-modal-description">
                                                                    Are you sure you want to rollback the migration <br/> <strong>{{ $migration['display_name'] }}</strong>?
                                                                </p>
                                                            </div>

                                                            <x-slot name="footerActions">
                                                                <x-filament::button color="gray" x-on:click="close">
                                                                    Cancel
                                                                </x-filament::button>

                                                                <x-filament::button 
                                                                    color="danger" 
                                                                    wire:click="rollbackMigration('{{ $migration['migration'] }}')"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="rollbackMigration('{{ $migration['migration'] }}')"
                                                                    x-on:click="close">
                                                                    <span wire:loading.remove wire:target="rollbackMigration('{{ $migration['migration'] }}')">
                                                                        Rollback Migration
                                                                    </span>
                                                                    <span wire:loading wire:target="rollbackMigration('{{ $migration['migration'] }}')">
                                                                        Rolling back...
                                                                    </span>
                                                                </x-filament::button>
                                                            </x-slot>
                                                        </x-filament::modal>
                                                    @else
                                                        <x-filament::badge color="gray" class="text-xs">
                                                            Cannot rollback individually
                                                        </x-filament::badge>
                                                    @endif
                                                @elseif(!$migration['file_exists'])
                                                    <x-filament::badge color="danger">File Missing</x-filament::badge>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
