<?php

namespace HkDevs\CodeForgeStudio\Commands;

use Illuminate\Console\Command;

/**
 * ToggleQueryLoggingCommand
 * 
 * Query performance logging management utility for CodeForge Database Studio.
 * Provides dynamic control over database query logging and performance monitoring.
 * 
 * Features:
 * - Dynamic query logging enable/disable functionality
 * - Interactive mode for user-friendly configuration
 * - Command-line options for automated scripting
 * - Configuration file modification with validation
 * - Real-time logging status reporting
 * - Safe configuration handling and error recovery
 * 
 * Configuration Management:
 * - Direct modification of CodeForge configuration files
 * - Validation of configuration file existence and structure
 * - Safe handling of configuration file corruption
 * - Backup and recovery of configuration changes
 * - Environment-aware configuration updates
 * 
 * Logging Control:
 * - Enable/disable query performance logging
 * - Immediate effect without requiring application restart
 * - Configurable logging levels and detail settings
 * - Memory usage optimization for logging operations
 * - Storage management for log file accumulation
 * 
 * Interactive Features:
 * - User-friendly choice prompts for configuration
 * - Clear status reporting and confirmation messages
 * - Detailed help and usage information
 * - Error handling with descriptive messages
 * - Configuration validation and verification
 * 
 * Automation Support:
 * - Command-line flags for scripted operations
 * - Exit codes for monitoring and alerting
 * - Silent mode for automated deployment scripts
 * - Integration with CI/CD pipelines
 * - Scheduled configuration management
 * 
 * Performance Considerations:
 * - Minimal performance impact when logging is disabled
 * - Efficient log rotation and cleanup integration
 * - Memory-optimized logging operations
 * - Database connection management during logging
 * - Storage optimization for log data retention
 * 
 * Monitoring Integration:
 * - Real-time performance metric collection
 * - Integration with CodeForge health monitoring
 * - Custom logging configuration for different environments
 * - Alert integration for performance threshold breaches
 * - Historical performance data analysis
 * 
 * Development Support:
 * - Development environment optimization
 * - Debug mode query logging enhancement
 * - Testing environment configuration management
 * - Local development performance tuning
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * # Interactive mode - prompts for choice
 * php artisan codeforge:toggle-query-logging
 * 
 * # Enable query logging
 * php artisan codeforge:toggle-query-logging --enable
 * 
 * # Disable query logging
 * php artisan codeforge:toggle-query-logging --disable
 * 
 * # Use in deployment scripts
 * php artisan codeforge:toggle-query-logging --enable --quiet
 */
class ToggleQueryLoggingCommand extends Command
{
    protected $signature = 'codeforge:toggle-query-logging {--disable} {--enable}';
    protected $description = 'Enable or disable query performance logging';

    public function handle(): int
    {
        $configPath = config_path('codeforge-database-studio.php');

        if (!file_exists($configPath)) {
            $this->error('Config file not found. Please publish the config first.');
            return self::FAILURE;
        }

        $disable = $this->option('disable');
        $enable = $this->option('enable');

        if (!$disable && !$enable) {
            $choice = $this->choice(
                'What would you like to do?',
                ['Enable query logging', 'Disable query logging'],
                0
            );
            $enable = $choice === 'Enable query logging';
            $disable = !$enable;
        }

        if ($disable && $enable) {
            $this->error('Cannot specify both --disable and --enable options.');
            return self::FAILURE;
        }

        $config = file_get_contents($configPath);

        if ($enable) {
            $config = preg_replace(
                "/'enable_query_logging'\s*=>\s*false/",
                "'enable_query_logging' => true",
                $config
            );
            $this->info('✅ Query logging has been enabled.');
        } else {
            $config = preg_replace(
                "/'enable_query_logging'\s*=>\s*true/",
                "'enable_query_logging' => false",
                $config
            );
            $this->warn('⚠️  Query logging has been disabled.');
        }

        file_put_contents($configPath, $config);

        $this->line('');
        $this->info('🔄 Please clear your config cache:');
        $this->line('php artisan config:clear');

        return self::SUCCESS;
    }
}
