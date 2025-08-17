<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\Widget;
use HkDevs\CodeForgeStudio\Services\LicenseValidationService;

class LicenseStatusWidget extends Widget
{
    protected static string $view = 'codeforge-database-studio::widgets.license-status';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -1; // Show at top

    public function __construct(
        private LicenseValidationService $licenseService
    ) {
        parent::__construct();
    }

    public function getLicenseData(): array
    {
        if (!config('codeforge-database-studio.license_validation.enabled')) {
            return [
                'status' => 'disabled',
                'message' => 'License validation is disabled',
            ];
        }

        $licenseInfo = $this->licenseService->getLicenseInfo();

        if (!$licenseInfo['valid']) {
            return [
                'status' => 'invalid',
                'message' => $licenseInfo['message'],
                'action_url' => 'https://anystack.sh/products/hkdevs-codeforge-database-studio',
            ];
        }

        $expiresAt = $licenseInfo['expires_at'] ? \Carbon\Carbon::parse($licenseInfo['expires_at']) : null;
        $daysUntilExpiry = $expiresAt ? now()->diffInDays($expiresAt, false) : null;

        if ($expiresAt && $daysUntilExpiry <= 0) {
            return [
                'status' => 'expired',
                'message' => 'License expired on ' . $expiresAt->format('M j, Y'),
                'action_url' => 'https://anystack.sh/products/hkdevs-codeforge-database-studio',
            ];
        }

        if ($expiresAt && $daysUntilExpiry <= 30) {
            return [
                'status' => 'expiring',
                'message' => "License expires in {$daysUntilExpiry} days",
                'expires_at' => $expiresAt->format('M j, Y'),
                'action_url' => 'https://anystack.sh/products/hkdevs-codeforge-database-studio',
            ];
        }

        return [
            'status' => 'valid',
            'message' => 'License is active and valid',
            'expires_at' => $expiresAt?->format('M j, Y'),
            'activations_left' => $licenseInfo['activations_left'],
        ];
    }

    public function checkForUpdates(): array
    {
        return $this->licenseService->checkForUpdates();
    }

    public static function canView(): bool
    {
        // Only show to authenticated users
        return auth()->check();
    }
}
