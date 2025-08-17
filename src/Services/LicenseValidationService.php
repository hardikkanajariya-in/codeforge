<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LicenseValidationService
{
    private const ANYSTACK_API_URL = 'https://api.anystack.sh/v1';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private string $productId = '9f9d2843-f44a-4d2a-ad42-c65ac7728bb1'
    ) {}

    /**
     * Validate license key with Anystack
     */
    public function validateLicense(string $licenseKey, ?string $fingerprint = null): array
    {
        $cacheKey = "codeforge_license_" . md5($licenseKey . $fingerprint);

        // Check cache first
        if ($cached = Cache::get($cacheKey)) {
            return $cached;
        }

        try {
            $response = Http::timeout(10)
                ->post(self::ANYSTACK_API_URL . '/licenses/validate', [
                    'product_id' => $this->productId,
                    'license_key' => $licenseKey,
                    'fingerprint' => $fingerprint ?? $this->getFingerprint(),
                ]);

            $result = [
                'valid' => $response->successful() && $response->json('valid', false),
                'message' => $response->json('message', 'Unknown error'),
                'license_info' => $response->json('license', []),
                'expires_at' => $response->json('expires_at'),
                'activations_left' => $response->json('activations_left'),
            ];

            // Cache successful validations
            if ($result['valid']) {
                Cache::put($cacheKey, $result, self::CACHE_TTL);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('CodeForge License Validation Failed', [
                'error' => $e->getMessage(),
                'license_key' => substr($licenseKey, 0, 8) . '...',
            ]);

            return [
                'valid' => false,
                'message' => 'License validation service unavailable',
                'license_info' => [],
                'expires_at' => null,
                'activations_left' => null,
            ];
        }
    }

    /**
     * Check if current installation has valid license
     */
    public function hasValidLicense(): bool
    {
        $licenseKey = config('codeforge-database-studio.license_key');

        if (!$licenseKey) {
            return false;
        }

        $validation = $this->validateLicense($licenseKey);
        return $validation['valid'];
    }

    /**
     * Get license information
     */
    public function getLicenseInfo(): array
    {
        $licenseKey = config('codeforge-database-studio.license_key');

        if (!$licenseKey) {
            return ['valid' => false, 'message' => 'No license key configured'];
        }

        return $this->validateLicense($licenseKey);
    }

    /**
     * Generate fingerprint for license activation
     */
    private function getFingerprint(): string
    {
        // Use domain or configured fingerprint
        $fingerprint = config('codeforge-database-studio.fingerprint');

        if ($fingerprint) {
            return $fingerprint;
        }

        // Fallback to request domain or app URL
        if (request() && request()->getHost()) {
            return request()->getHost();
        }

        return parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
    }

    /**
     * Activate license for current fingerprint
     */
    public function activateLicense(string $licenseKey, ?string $fingerprint = null): array
    {
        try {
            $response = Http::timeout(10)
                ->post(self::ANYSTACK_API_URL . '/licenses/activate', [
                    'product_id' => $this->productId,
                    'license_key' => $licenseKey,
                    'fingerprint' => $fingerprint ?? $this->getFingerprint(),
                ]);

            $result = [
                'success' => $response->successful(),
                'message' => $response->json('message', 'Unknown error'),
                'activation_id' => $response->json('activation_id'),
            ];

            if ($result['success']) {
                // Clear cache to force revalidation
                Cache::forget("codeforge_license_" . md5($licenseKey . ($fingerprint ?? $this->getFingerprint())));
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('CodeForge License Activation Failed', [
                'error' => $e->getMessage(),
                'license_key' => substr($licenseKey, 0, 8) . '...',
            ]);

            return [
                'success' => false,
                'message' => 'License activation service unavailable',
                'activation_id' => null,
            ];
        }
    }

    /**
     * Check for plugin updates
     */
    public function checkForUpdates(): array
    {
        try {
            $response = Http::timeout(10)
                ->get(self::ANYSTACK_API_URL . '/products/' . $this->productId . '/releases');

            if ($response->successful()) {
                $releases = $response->json('releases', []);
                $currentVersion = config('codeforge-database-studio.version', '1.0.0');

                $latestRelease = collect($releases)
                    ->sortByDesc('created_at')
                    ->first();

                return [
                    'has_update' => $latestRelease && version_compare($latestRelease['version'], $currentVersion, '>'),
                    'latest_version' => $latestRelease['version'] ?? null,
                    'current_version' => $currentVersion,
                    'changelog_url' => $latestRelease['changelog_url'] ?? null,
                ];
            }

            return ['has_update' => false, 'message' => 'Unable to check for updates'];
        } catch (\Exception $e) {
            Log::error('CodeForge Update Check Failed', ['error' => $e->getMessage()]);
            return ['has_update' => false, 'message' => 'Update service unavailable'];
        }
    }
}
