<?php

namespace HkDevs\CodeForgeStudio\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use HkDevs\CodeForgeStudio\Services\LicenseValidationService;
use Filament\Notifications\Notification;

class ValidateLicense
{
    public function __construct(
        private LicenseValidationService $licenseService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip validation if disabled
        if (!config('codeforge-database-studio.license_validation.enabled')) {
            return $next($request);
        }

        // Skip validation for non-CodeForge routes
        if (!$this->isCodeForgeRoute($request)) {
            return $next($request);
        }

        $licenseInfo = $this->licenseService->getLicenseInfo();

        if (!$licenseInfo['valid']) {
            $this->showLicenseWarning($licenseInfo['message']);

            // In development, allow access but show warning
            if (app()->environment('local', 'development')) {
                return $next($request);
            }

            // In production, block access
            return redirect()
                ->route('filament.admin.pages.dashboard')
                ->with('error', 'Invalid or expired license. Please contact support.');
        }

        // Check for upcoming expiration
        if (isset($licenseInfo['expires_at'])) {
            $expiresAt = \Carbon\Carbon::parse($licenseInfo['expires_at']);
            $daysUntilExpiry = now()->diffInDays($expiresAt, false);

            if ($daysUntilExpiry <= 30 && $daysUntilExpiry > 0) {
                $this->showExpirationWarning($daysUntilExpiry);
            }
        }

        return $next($request);
    }

    private function isCodeForgeRoute(Request $request): bool
    {
        $path = $request->path();

        return str_contains($path, 'codeforge') ||
            str_contains($path, 'database-studio') ||
            str_contains($path, 'schema-designer') ||
            str_contains($path, 'migration-manager') ||
            str_contains($path, 'health-monitoring');
    }

    private function showLicenseWarning(string $message): void
    {
        Notification::make()
            ->title('License Issue')
            ->body($message . ' Please contact contact@hardikkanajariya.in')
            ->warning()
            ->persistent()
            ->send();
    }

    private function showExpirationWarning(int $daysUntilExpiry): void
    {
        Notification::make()
            ->title('License Expiring Soon')
            ->body("Your CodeForge license expires in {$daysUntilExpiry} days. Please renew to continue receiving updates.")
            ->warning()
            ->actions([
                \Filament\Notifications\Actions\Action::make('renew')
                    ->label('Renew License')
                    ->url('https://anystack.sh/products/hkdevs-codeforge-database-studio')
                    ->openUrlInNewTab(),
            ])
            ->send();
    }
}
