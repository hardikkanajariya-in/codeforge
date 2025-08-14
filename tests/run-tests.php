#!/usr/bin/env php
<?php

/**
 * CodeForge Database Studio - Test Suite Runner
 * 
 * This script provides an easy way to run the comprehensive test suite
 * for Visual Schema Designer and Intelligent Data Seeding functionality.
 * 
 * @package   HkDevs\CodeForgeStudio
 * @author    Hardik Kanajariya <hardikkanajariya@yahoo.com>
 * @copyright 2024 HkDevs (hardikkanajariya.in)
 * @license   Commercial License
 * @version   1.0.0
 */

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.' . PHP_EOL);
}

class TestSuiteRunner
{
    private $basePath;
    private $phpunit;
    private $options = [];

    public function __construct()
    {
        $this->basePath = dirname(__DIR__, 3); // Navigate to project root
        $this->phpunit = $this->basePath . '/vendor/bin/phpunit';

        if (!file_exists($this->phpunit)) {
            die("❌ PHPUnit not found at: {$this->phpunit}" . PHP_EOL);
        }
    }

    public function run($args = [])
    {
        $this->parseArguments($args);

        echo "🚀 CodeForge Database Studio Test Suite Runner" . PHP_EOL;
        echo "===============================================" . PHP_EOL;
        echo "📍 Base Path: {$this->basePath}" . PHP_EOL;
        echo "🔧 PHPUnit: {$this->phpunit}" . PHP_EOL;
        echo PHP_EOL;

        if (isset($this->options['help'])) {
            $this->showHelp();
            return;
        }

        if (isset($this->options['list'])) {
            $this->listTests();
            return;
        }

        $this->runTests();
    }

    private function parseArguments($args)
    {
        $options = [
            'help' => false,
            'list' => false,
            'verbose' => false,
            'coverage' => false,
            'suite' => 'all',
            'filter' => null,
        ];

        foreach ($args as $arg) {
            switch ($arg) {
                case '--help':
                case '-h':
                    $options['help'] = true;
                    break;
                case '--list':
                    $options['list'] = true;
                    break;
                case '--verbose':
                case '-v':
                    $options['verbose'] = true;
                    break;
                case '--coverage':
                    $options['coverage'] = true;
                    break;
                case '--schema':
                    $options['suite'] = 'schema';
                    break;
                case '--seeding':
                    $options['suite'] = 'seeding';
                    break;
                case '--integration':
                    $options['suite'] = 'integration';
                    break;
                default:
                    if (strpos($arg, '--filter=') === 0) {
                        $options['filter'] = substr($arg, 9);
                    }
                    break;
            }
        }

        $this->options = $options;
    }

    private function showHelp()
    {
        echo "📖 Usage: php run-tests.php [options]" . PHP_EOL;
        echo PHP_EOL;
        echo "Options:" . PHP_EOL;
        echo "  --help, -h         Show this help message" . PHP_EOL;
        echo "  --list             List all available tests" . PHP_EOL;
        echo "  --verbose, -v      Run tests with verbose output" . PHP_EOL;
        echo "  --coverage         Generate code coverage report" . PHP_EOL;
        echo "  --schema           Run only Visual Schema Designer tests" . PHP_EOL;
        echo "  --seeding          Run only Intelligent Data Seeding tests" . PHP_EOL;
        echo "  --integration      Run only integration tests" . PHP_EOL;
        echo "  --filter=<pattern> Run only tests matching the pattern" . PHP_EOL;
        echo PHP_EOL;
        echo "Examples:" . PHP_EOL;
        echo "  php run-tests.php                    # Run all tests" . PHP_EOL;
        echo "  php run-tests.php --schema           # Visual Schema Designer tests only" . PHP_EOL;
        echo "  php run-tests.php --seeding          # Data Seeding tests only" . PHP_EOL;
        echo "  php run-tests.php --verbose          # Run with detailed output" . PHP_EOL;
        echo "  php run-tests.php --filter=context   # Run tests with 'context' in name" . PHP_EOL;
        echo PHP_EOL;
    }

    private function listTests()
    {
        echo "📋 Available Test Suites:" . PHP_EOL;
        echo PHP_EOL;

        echo "🎨 Visual Schema Designer Tests (TC-SCHEMA-001 to TC-SCHEMA-005):" . PHP_EOL;
        echo "   • test_interactive_schema_visualization_interface" . PHP_EOL;
        echo "   • test_visual_relationship_mapping" . PHP_EOL;
        echo "   • test_schema_documentation_generation" . PHP_EOL;
        echo "   • test_migration_and_documentation_export" . PHP_EOL;
        echo "   • test_documentation_export" . PHP_EOL;
        echo PHP_EOL;

        echo "🌱 Intelligent Data Seeding Tests (TC-SEED-001 to TC-SEED-005):" . PHP_EOL;
        echo "   • test_context_aware_data_generation" . PHP_EOL;
        echo "   • test_custom_seeding_templates" . PHP_EOL;
        echo "   • test_relationship_aware_seeding" . PHP_EOL;
        echo "   • test_bulk_data_operations" . PHP_EOL;
        echo "   • test_seeder_management_and_execution" . PHP_EOL;
        echo PHP_EOL;

        echo "🔗 Integration Tests:" . PHP_EOL;
        echo "   • test_integrated_test_suite_structure" . PHP_EOL;
        echo "   • test_cross_feature_integration" . PHP_EOL;
        echo "   • test_performance_validation" . PHP_EOL;
        echo PHP_EOL;
    }

    private function runTests()
    {
        $testPaths = $this->getTestPaths();

        if (empty($testPaths)) {
            echo "❌ No tests found for suite: {$this->options['suite']}" . PHP_EOL;
            return;
        }

        $totalTests = count($testPaths);
        $passedTests = 0;
        $failedTests = 0;

        echo "🧪 Running {$totalTests} test suite(s)..." . PHP_EOL;
        echo PHP_EOL;

        foreach ($testPaths as $suiteName => $path) {
            echo "📂 Running {$suiteName}..." . PHP_EOL;

            $command = $this->buildPhpunitCommand($path);
            $output = [];
            $returnCode = 0;

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                echo "✅ {$suiteName}: PASSED" . PHP_EOL;
                $passedTests++;
            } else {
                echo "❌ {$suiteName}: FAILED" . PHP_EOL;
                $failedTests++;

                if ($this->options['verbose']) {
                    echo "   Error output:" . PHP_EOL;
                    foreach ($output as $line) {
                        echo "   {$line}" . PHP_EOL;
                    }
                }
            }

            echo PHP_EOL;
        }

        // Summary
        echo "📊 Test Results Summary:" . PHP_EOL;
        echo "================================" . PHP_EOL;
        echo "✅ Passed: {$passedTests}" . PHP_EOL;
        echo "❌ Failed: {$failedTests}" . PHP_EOL;
        echo "📈 Total:  {$totalTests}" . PHP_EOL;
        echo PHP_EOL;

        if ($failedTests === 0) {
            echo "🎉 All tests passed successfully!" . PHP_EOL;
        } else {
            echo "⚠️  Some tests failed. Please review the output above." . PHP_EOL;
        }
    }

    private function getTestPaths()
    {
        $basePath = $this->basePath . '/packages/codeforge-database-studio/tests/Feature';
        $paths = [];

        switch ($this->options['suite']) {
            case 'schema':
                $paths['Visual Schema Designer'] = $basePath . '/VisualSchemaDesigner/ComprehensiveVisualSchemaDesignerTest.php';
                break;

            case 'seeding':
                $paths['Intelligent Data Seeding'] = $basePath . '/IntelligentDataSeeding/ComprehensiveIntelligentDataSeedingTest.php';
                break;

            case 'integration':
                $paths['Integration Tests'] = $basePath . '/VisualSchemaDesignerAndDataSeedingTestSuite.php';
                break;

            case 'all':
            default:
                $paths['Visual Schema Designer'] = $basePath . '/VisualSchemaDesigner/ComprehensiveVisualSchemaDesignerTest.php';
                $paths['Intelligent Data Seeding'] = $basePath . '/IntelligentDataSeeding/ComprehensiveIntelligentDataSeedingTest.php';
                $paths['Integration Tests'] = $basePath . '/VisualSchemaDesignerAndDataSeedingTestSuite.php';
                break;
        }

        // Filter out non-existent files
        $paths = array_filter($paths, function ($path) {
            return file_exists($path);
        });

        return $paths;
    }

    private function buildPhpunitCommand($testPath)
    {
        $command = "\"{$this->phpunit}\"";

        if ($this->options['verbose']) {
            $command .= " --verbose";
        }

        if ($this->options['coverage']) {
            $command .= " --coverage-text";
        }

        if ($this->options['filter']) {
            $command .= " --filter=\"{$this->options['filter']}\"";
        }

        $command .= " \"{$testPath}\"";

        return $command;
    }
}

// Run the test suite
$runner = new TestSuiteRunner();
$runner->run(array_slice($argv, 1));
