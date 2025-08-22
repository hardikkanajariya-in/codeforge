<?php

/**
 * CodeForge Database Studio - Automated Installer
 * Professional Laravel Database Management Plugin
 * 
 * @author HkDevs
 * @website https://codeforge.hardikkanajariya.in
 * @version 1.0.0
 */

class CodeForgeInstaller
{
    private $errors = [];
    private $success = [];
    private $requirements = [
        'php' => '8.1',
        'laravel' => '10.0',
        'filament' => '3.0'
    ];

    public function install()
    {
        try {
            $this->checkRequirements();
            $this->publishAssets();
            $this->runMigrations();
            $this->createConfig();
            $this->clearCache();
            $this->success[] = "CodeForge Database Studio installed successfully!";
            
            return $this->getInstallationSummary();
            
        } catch (Exception $e) {
            $this->errors[] = "Installation failed: " . $e->getMessage();
            return $this->getInstallationSummary();
        }
    }

    private function checkRequirements()
    {
        // Check PHP version
        if (version_compare(PHP_VERSION, $this->requirements['php'], '<')) {
            throw new Exception("PHP {$this->requirements['php']} or higher required. Current: " . PHP_VERSION);
        }

        // Check Laravel
        if (!class_exists('Illuminate\Foundation\Application')) {
            throw new Exception("Laravel framework not found");
        }

        // Check Filament
        if (!class_exists('Filament\FilamentServiceProvider')) {
            throw new Exception("FilamentPHP not found. Please install Filament first.");
        }

        $this->success[] = "System requirements verified";
    }

    private function publishAssets()
    {
        exec('php artisan vendor:publish --tag="codeforge-database-studio-config" --force', $output, $return);
        if ($return !== 0) {
            throw new Exception("Failed to publish configuration files");
        }

        exec('php artisan vendor:publish --tag="codeforge-database-studio-migrations" --force', $output, $return);
        if ($return !== 0) {
            throw new Exception("Failed to publish migration files");
        }

        $this->success[] = "Assets published successfully";
    }

    private function runMigrations()
    {
        exec('php artisan migrate --force', $output, $return);
        if ($return !== 0) {
            throw new Exception("Migration failed. Please check your database configuration.");
        }

        $this->success[] = "Database migrations completed";
    }

    private function createConfig()
    {
        $configPath = base_path('config/codeforge-database-studio.php');
        if (!file_exists($configPath)) {
            throw new Exception("Configuration file not found after publishing");
        }

        $this->success[] = "Configuration file created";
    }

    private function clearCache()
    {
        $commands = [
            'php artisan config:clear',
            'php artisan cache:clear',
            'php artisan view:clear',
            'php artisan route:clear'
        ];

        foreach ($commands as $command) {
            exec($command, $output, $return);
            if ($return !== 0) {
                $this->errors[] = "Warning: Failed to clear cache with command: $command";
            }
        }

        $this->success[] = "Application cache cleared";
    }

    private function getInstallationSummary()
    {
        return [
            'success' => empty($this->errors),
            'messages' => array_merge($this->success, $this->errors),
            'next_steps' => [
                '1. Add CodeForgeStudioPlugin::make() to your Filament panel provider',
                '2. Visit your Filament admin panel',
                '3. Look for "Database Overview" in the navigation menu',
                '4. Start managing your database with CodeForge!'
            ]
        ];
    }
}

// Auto-run installation if accessed directly
if (basename($_SERVER['PHP_SELF']) === 'install.php') {
    $installer = new CodeForgeInstaller();
    $result = $installer->install();
    
    echo "<h1>CodeForge Database Studio Installation</h1>";
    
    if ($result['success']) {
        echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>✅ Installation Successful!</h3>";
    } else {
        echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>❌ Installation Failed</h3>";
    }
    
    foreach ($result['messages'] as $message) {
        echo "<p>• $message</p>";
    }
    echo "</div>";
    
    if ($result['success']) {
        echo "<div style='background: #cce5ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>📋 Next Steps:</h3>";
        foreach ($result['next_steps'] as $step) {
            echo "<p>$step</p>";
        }
        echo "</div>";
    }
}
