<div
    class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    @php
        $licenseData = $this->getLicenseData();
        $updateData = $this->checkForUpdates();

        $statusColors = [
            'valid' => 'success',
            'expiring' => 'warning',
            'expired' => 'danger',
            'invalid' => 'danger',
            'disabled' => 'gray'
        ];

        $statusIcons = [
            'valid' => 'heroicon-o-check-circle',
            'expiring' => 'heroicon-o-exclamation-triangle',
            'expired' => 'heroicon-o-x-circle',
            'invalid' => 'heroicon-o-x-circle',
            'disabled' => 'heroicon-o-minus-circle'
        ];

        $color = $statusColors[$licenseData['status']] ?? 'gray';
        $icon = $statusIcons[$licenseData['status']] ?? 'heroicon-o-information-circle';
    @endphp

    <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-500/20">
                <x-filament::icon :icon="$icon" class="h-6 w-6 text-{{ $color }}-600 dark:text-{{ $color }}-400" />
            </div>
        </div>

        <div class="flex-1 min-w-0">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                CodeForge Database Studio License
            </h3>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $licenseData['message'] }}
            </p>

            @if(isset($licenseData['expires_at']))
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    Expires: {{ $licenseData['expires_at'] }}
                </p>
            @endif

            @if(isset($licenseData['activations_left']) && $licenseData['activations_left'] !== null)
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    Activations remaining: {{ $licenseData['activations_left'] }}
                </p>
            @endif
        </div>

        <div class="flex-shrink-0 flex flex-col gap-2">
            @if(in_array($licenseData['status'], ['expired', 'invalid', 'expiring']) && isset($licenseData['action_url']))
                <x-filament::button :href="$licenseData['action_url']" target="_blank"
                    color="{{ $licenseData['status'] === 'expiring' ? 'warning' : 'primary' }}" size="sm">
                    {{ $licenseData['status'] === 'expiring' ? 'Renew License' : 'Get License' }}
                </x-filament::button>
            @endif

            @if($updateData['has_update'] ?? false)
                <x-filament::button :href="$updateData['changelog_url'] ?? '#'" target="_blank" color="success" size="sm"
                    outlined>
                    Update Available ({{ $updateData['latest_version'] }})
                </x-filament::button>
            @endif
        </div>
    </div>

    @if($licenseData['status'] === 'disabled')
        <div class="mt-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                License validation is disabled. To enable license checking, set
                <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">CODEFORGE_LICENSE_VALIDATION=true</code>
                in your .env file.
            </p>
        </div>
    @endif

    @if(in_array($licenseData['status'], ['expired', 'invalid']))
        <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-700 dark:text-red-400">
                <strong>License Issue:</strong> Some features may be restricted.
                Please ensure you have a valid license to continue using all features.
            </p>
            @if(app()->environment('local', 'development'))
                <p class="text-xs text-red-600 dark:text-red-500 mt-2">
                    <em>Development mode: Full access granted for testing purposes.</em>
                </p>
            @endif
        </div>
    @endif
</div>
