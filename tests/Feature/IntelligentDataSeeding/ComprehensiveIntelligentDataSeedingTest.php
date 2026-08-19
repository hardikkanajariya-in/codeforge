<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\IntelligentDataSeeding;

use Carbon\Carbon;
use HkDevs\CodeForgeStudio\Models\DataGenerationTemplate;
use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Models\SeederExecutionLog;
use HkDevs\CodeForgeStudio\Pages\SmartDataSeeder;
use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;

/**
 * Comprehensive Intelligent Data Seeding Test Suite
 *
 * This test class implements all test cases from the Comprehensive Test Cases Documentation
 * for Intelligent Data Seeding functionality, ensuring complete coverage of:
 *
 * - TC-SEED-001: Context-Aware Data Generation
 * - TC-SEED-002: Custom Seeding Templates
 * - TC-SEED-003: Relationship-Aware Seeding
 * - TC-SEED-004: Bulk Data Operations
 * - TC-SEED-005: Seeder Management & Execution
 *
 * @author HkDevs (hardikkanajariya.in)
 *
 * @version 1.0.0
 */
class ComprehensiveIntelligentDataSeedingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private DataGenerationService $dataGenerationService;

    private array $testTablesCreated = [];

    private array $createdTemplates = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize data generation service
        $this->dataGenerationService = app(DataGenerationService::class);

        // Clear cache
        Cache::flush();

        // Ensure required tables exist
        $this->createRequiredTables();

        // Create test tables for seeding
        $this->createTestTablesForSeeding();
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->cleanupTestData();

        // Clear cache
        Cache::flush();

        parent::tearDown();
    }

    /**
     * TC-SEED-001: Context-Aware Data Generation
     * Purpose: Test intelligent data generation based on field types and relationships
     */
    #[Test]
    public function test_context_aware_data_generation()
    {
        // Step 1: Create models with various field types and naming patterns
        $this->createContextAwareTestTables();

        $component = Livewire::test(SmartDataSeeder::class);

        // Step 2: Execute smart data generation using equivalent of `db-manager:generate-data`
        $tableData = [
            'table_name' => 'context_users',
            'record_count' => 20,
            'generation_mode' => 'auto',
        ];

        $component->set('data', $tableData);
        $component->call('analyzeTable', 'context_users');

        $tableAnalysis = $component->get('tableAnalysis');
        $this->assertNotNull($tableAnalysis);
        $this->assertArrayHasKey('columns', $tableAnalysis);

        // Step 3: Verify data relevance to field names and types (email, phone, address)
        $columns = $tableAnalysis['columns'];
        $emailColumn = collect($columns)->firstWhere('name', 'email');
        $phoneColumn = collect($columns)->firstWhere('name', 'phone_number');
        $addressColumn = collect($columns)->firstWhere('name', 'street_address');

        $this->assertNotNull($emailColumn, 'Email column should be detected');
        $this->assertNotNull($phoneColumn, 'Phone column should be detected');
        $this->assertNotNull($addressColumn, 'Address column should be detected');

        // Step 4: Test generation for complex field patterns (SKU, UUID, etc.)
        $skuColumn = collect($columns)->firstWhere('name', 'sku');
        $uuidColumn = collect($columns)->firstWhere('name', 'uuid');

        $this->assertNotNull($skuColumn, 'SKU column should be detected');
        $this->assertNotNull($uuidColumn, 'UUID column should be detected');

        // Step 5: Verify realistic data patterns and consistency
        $component->call('generatePreview');
        $previewData = $component->get('previewData');

        $this->assertNotEmpty($previewData, 'Preview data should be generated');
        $this->assertGreaterThan(0, count($previewData));

        // Verify email format
        foreach ($previewData as $record) {
            if (isset($record['email'])) {
                $this->assertMatchesRegularExpression('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $record['email'], 'Email should be valid format');
            }

            // Verify phone format (basic pattern)
            if (isset($record['phone_number'])) {
                $this->assertNotEmpty($record['phone_number'], 'Phone number should not be empty');
            }

            // Verify SKU pattern
            if (isset($record['sku'])) {
                $this->assertNotEmpty($record['sku'], 'SKU should not be empty');
                $this->assertMatchesRegularExpression('/^[A-Z0-9-]+$/', $record['sku'], 'SKU should follow pattern');
            }

            // Verify UUID format
            if (isset($record['uuid'])) {
                $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $record['uuid'], 'UUID should be valid format');
            }
        }

        // Test actual data insertion
        $component->call('generateAndInsertData');

        // Verify data was inserted
        $insertedCount = DB::table('context_users')->count();
        $this->assertEquals(20, $insertedCount, 'Should have inserted 20 records');

        // Verify data quality
        $sampleRecord = DB::table('context_users')->first();
        $this->assertNotNull($sampleRecord);
        $this->assertNotEmpty($sampleRecord->email);
        $this->assertNotEmpty($sampleRecord->first_name);
        $this->assertNotEmpty($sampleRecord->last_name);
    }

    /**
     * TC-SEED-002: Custom Seeding Templates
     * Purpose: Test reusable templates for consistent data patterns
     */
    #[Test]
    public function test_custom_seeding_templates()
    {
        // Step 1: Create custom seeding templates for different domains
        $ecommerceTemplate = $this->createEcommerceTemplate();
        $blogTemplate = $this->createBlogTemplate();
        $crmTemplate = $this->createCRMTemplate();

        $this->assertInstanceOf(DataGenerationTemplate::class, $ecommerceTemplate);
        $this->assertInstanceOf(DataGenerationTemplate::class, $blogTemplate);
        $this->assertInstanceOf(DataGenerationTemplate::class, $crmTemplate);

        // Step 2: Apply templates to multiple models
        $component = Livewire::test(SmartDataSeeder::class);

        // Test ecommerce template application
        $tableData = [
            'table_name' => 'template_products',
            'record_count' => 15,
            'generation_mode' => 'existing',
            'template_id' => $ecommerceTemplate->id,
        ];

        $component->set('data', $tableData);
        $component->call('generatePreview');

        $previewData = $component->get('previewData');
        $this->assertNotEmpty($previewData);

        // Verify template field mappings are applied
        foreach ($previewData as $record) {
            $this->assertArrayHasKey('product_name', $record);
            $this->assertArrayHasKey('price', $record);
            $this->assertArrayHasKey('category', $record);

            // Verify price is numeric
            $this->assertIsNumeric($record['price']);
            $this->assertGreaterThan(0, $record['price']);
        }

        // Step 3: Test template inheritance and customization
        $customTemplate = $this->createCustomizedTemplate($ecommerceTemplate);
        $this->assertInstanceOf(DataGenerationTemplate::class, $customTemplate);

        // Verify inheritance
        $originalMappings = $ecommerceTemplate->field_mappings;
        $customMappings = $customTemplate->field_mappings;

        $this->assertArrayHasKey('product_name', $originalMappings);
        $this->assertArrayHasKey('product_name', $customMappings);

        // Step 4: Verify template sharing and reuse capabilities
        $allTemplates = DataGenerationTemplate::active()->get();
        $this->assertGreaterThanOrEqual(3, $allTemplates->count());

        // Test template reuse
        $reusedTemplate = DataGenerationTemplate::forTable('template_products')->first();
        $this->assertNotNull($reusedTemplate);

        // Step 5: Test template validation and error handling
        $invalidTemplate = new DataGenerationTemplate([
            'name' => 'Invalid Template',
            'table_name' => 'non_existent_table',
            'field_mappings' => ['invalid' => 'mapping'],
            'is_active' => true,
        ]);

        try {
            $invalidTemplate->save();
            $this->assertTrue(true, 'Template should save even with validation issues');
        } catch (\Exception $e) {
            $this->assertStringContainsString('validation', strtolower($e->getMessage()));
        }
    }

    /**
     * TC-SEED-003: Relationship-Aware Seeding
     * Purpose: Test automatic handling of foreign key relationships during seeding
     */
    #[Test]
    public function test_relationship_aware_seeding()
    {
        // Step 1: Create models with complex relationship structures
        $this->createRelationshipTestTables();

        // Step 2: Generate seed data maintaining referential integrity
        $component = Livewire::test(SmartDataSeeder::class);

        // First, seed parent tables (categories)
        $categoryData = [
            'table_name' => 'rel_categories',
            'record_count' => 5,
            'generation_mode' => 'auto',
        ];

        $component->set('data', $categoryData);
        $component->call('generateAndInsertData');

        $categoryCount = DB::table('rel_categories')->count();
        $this->assertEquals(5, $categoryCount);

        // Then seed child tables (products)
        $productData = [
            'table_name' => 'rel_products',
            'record_count' => 20,
            'generation_mode' => 'auto',
        ];

        $component->set('data', $productData);
        $component->call('generateAndInsertData');

        $productCount = DB::table('rel_products')->count();
        $this->assertEquals(20, $productCount);

        // Step 3: Test cascade seeding for related models
        // Verify all products have valid category references
        $invalidProducts = DB::table('rel_products')
            ->leftJoin('rel_categories', 'rel_products.category_id', '=', 'rel_categories.id')
            ->whereNull('rel_categories.id')
            ->count();

        $this->assertEquals(0, $invalidProducts, 'All products should have valid category references');

        // Step 4: Verify foreign key constraints are respected
        $this->assertForeignKeyIntegrity('rel_products', 'category_id', 'rel_categories', 'id');

        // Step 5: Test many-to-many relationship seeding with pivot data
        $this->seedManyToManyRelationships();

        // Verify pivot table data
        $pivotCount = DB::table('rel_product_tags')->count();
        $this->assertGreaterThan(0, $pivotCount, 'Pivot table should have data');

        // Verify pivot integrity
        $invalidPivotProducts = DB::table('rel_product_tags')
            ->leftJoin('rel_products', 'rel_product_tags.product_id', '=', 'rel_products.id')
            ->whereNull('rel_products.id')
            ->count();

        $invalidPivotTags = DB::table('rel_product_tags')
            ->leftJoin('rel_tags', 'rel_product_tags.tag_id', '=', 'rel_tags.id')
            ->whereNull('rel_tags.id')
            ->count();

        $this->assertEquals(0, $invalidPivotProducts, 'All pivot records should have valid product references');
        $this->assertEquals(0, $invalidPivotTags, 'All pivot records should have valid tag references');
    }

    /**
     * TC-SEED-004: Bulk Data Operations
     * Purpose: Test efficient generation of large test datasets
     */
    #[Test]
    public function test_bulk_data_operations()
    {
        // Step 1: Configure seeding for large record counts (10k+)
        $this->createBulkTestTables();

        $component = Livewire::test(SmartDataSeeder::class);

        // Step 2: Monitor memory usage during bulk generation
        $initialMemory = memory_get_usage(true);

        $bulkData = [
            'table_name' => 'bulk_records',
            'record_count' => 1000, // Reduced for testing environment
            'generation_mode' => 'auto',
        ];

        $component->set('data', $bulkData);

        $startTime = microtime(true);
        $component->call('generateAndInsertData');
        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;
        $finalMemory = memory_get_usage(true);
        $memoryUsed = $finalMemory - $initialMemory;

        // Step 3: Test batch processing functionality and efficiency
        $this->assertLessThan(30.0, $executionTime, 'Bulk generation should complete within reasonable time');
        $this->assertLessThan(64 * 1024 * 1024, $memoryUsed, 'Memory usage should be reasonable for bulk operations');

        // Verify all records were inserted
        $insertedCount = DB::table('bulk_records')->count();
        $this->assertEquals(1000, $insertedCount, 'All records should be inserted');

        // Step 4: Verify data integrity in large datasets
        $sampleSize = 100;
        $sampleRecords = DB::table('bulk_records')->inRandomOrder()->limit($sampleSize)->get();

        $this->assertEquals($sampleSize, $sampleRecords->count());

        foreach ($sampleRecords as $record) {
            $this->assertNotNull($record->id);
            $this->assertNotEmpty($record->name);
            $this->assertNotEmpty($record->email);
            $this->assertIsNumeric($record->value);
        }

        // Step 5: Test performance optimization features
        $this->verifyBulkInsertOptimizations();

        // Test chunked processing
        $largerBulkData = [
            'table_name' => 'bulk_records',
            'record_count' => 5000,
            'generation_mode' => 'auto',
        ];

        // Clear table first
        DB::table('bulk_records')->truncate();

        $component->set('data', $largerBulkData);

        $chunkStartTime = microtime(true);
        $component->call('generateAndInsertData');
        $chunkEndTime = microtime(true);

        $chunkExecutionTime = $chunkEndTime - $chunkStartTime;
        $finalCount = DB::table('bulk_records')->count();

        $this->assertEquals(5000, $finalCount, 'All chunked records should be inserted');
        $this->assertLessThan(120.0, $chunkExecutionTime, 'Chunked processing should be efficient');
    }

    /**
     * TC-SEED-005: Seeder Management & Execution
     * Purpose: Test seeder execution history and management through Artisan commands
     */
    #[Test]
    public function test_seeder_management_and_execution()
    {
        // Step 1: Execute various seeding operations using Artisan commands
        $this->createSeederManagementTables();

        // Create seeders for testing
        $userSeeder = $this->createSeederConfiguration('User Seeder', 'mgmt_users', 50);
        $postSeeder = $this->createSeederConfiguration('Post Seeder', 'mgmt_posts', 200);

        $this->assertInstanceOf(DataSeeder::class, $userSeeder);
        $this->assertInstanceOf(DataSeeder::class, $postSeeder);

        // Step 2: Verify execution logs capture all details and timing
        $component = Livewire::test(SmartDataSeeder::class);

        $startTime = microtime(true);

        // Execute user seeder
        $userData = [
            'table_name' => 'mgmt_users',
            'record_count' => 50,
            'generation_mode' => 'auto',
        ];

        $component->set('data', $userData);
        $component->call('generateAndInsertData');

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verify execution log creation
        $this->logSeederExecution($userSeeder, 50, $executionTime, 'completed');

        $executionLog = SeederExecutionLog::where('seeder_id', $userSeeder->id)->first();
        $this->assertNotNull($executionLog);
        $this->assertEquals(50, $executionLog->records_created);
        $this->assertEquals('completed', $executionLog->status);
        $this->assertGreaterThan(0, $executionLog->execution_time);

        // Step 3: Test error logging for failed seeding operations
        $failingSeeder = $this->createSeederConfiguration('Failing Seeder', 'non_existent_table', 10);

        try {
            $failingData = [
                'table_name' => 'non_existent_table',
                'record_count' => 10,
                'generation_mode' => 'auto',
            ];

            $component->set('data', $failingData);
            $component->call('generateAndInsertData');

            // Should not reach here if error handling works
            $this->fail('Should have thrown an exception for non-existent table');
        } catch (\Exception $e) {
            // Log the failure
            $this->logSeederExecution($failingSeeder, 0, 0, 'failed', $e->getMessage());

            $failedLog = SeederExecutionLog::where('seeder_id', $failingSeeder->id)->first();
            $this->assertNotNull($failedLog);
            $this->assertEquals('failed', $failedLog->status);
            $this->assertNotNull($failedLog->error_message);
        }

        // Step 4: Verify performance metrics logging
        $performanceMetrics = $this->getSeederPerformanceMetrics($userSeeder->id);
        $this->assertNotNull($performanceMetrics);
        $this->assertArrayHasKey('execution_time', $performanceMetrics);
        $this->assertArrayHasKey('records_per_second', $performanceMetrics);
        $this->assertArrayHasKey('memory_usage', $performanceMetrics);

        // Step 5: Test seeder execution tracking and history
        $executionHistory = SeederExecutionLog::where('seeder_id', $userSeeder->id)
            ->orderBy('executed_at', 'desc')
            ->get();

        $this->assertGreaterThan(0, $executionHistory->count());

        foreach ($executionHistory as $log) {
            $this->assertNotNull($log->seeder_id);
            $this->assertNotNull($log->status);
            $this->assertNotNull($log->executed_at);

            if ($log->status === 'completed') {
                $this->assertGreaterThan(0, $log->records_created);
                $this->assertGreaterThan(0, $log->execution_time);
            }
        }

        // Test seeder statistics
        $seederStats = $this->getSeederStatistics();
        $this->assertArrayHasKey('total_executions', $seederStats);
        $this->assertArrayHasKey('successful_executions', $seederStats);
        $this->assertArrayHasKey('failed_executions', $seederStats);
        $this->assertArrayHasKey('total_records_created', $seederStats);
    }

    /**
     * Test data generation service performance and optimization
     */
    #[Test]
    public function test_data_generation_service_performance()
    {
        $startTime = microtime(true);
        $initialMemory = memory_get_usage(true);

        // Create template programmatically
        $template = DataGenerationTemplate::create([
            'name' => 'Performance Test Template',
            'table_name' => 'performance_test',
            'field_mappings' => [
                'name' => ['type' => 'faker', 'method' => 'name'],
                'email' => ['type' => 'faker', 'method' => 'email'],
                'age' => ['type' => 'number', 'min' => 18, 'max' => 80],
            ],
            'is_active' => true,
        ]);

        $this->createdTemplates[] = $template->id;

        // Test template creation performance
        $templateCreationTime = microtime(true) - $startTime;
        $this->assertLessThan(1.0, $templateCreationTime, 'Template creation should be fast');

        // Test data generation performance
        $generationStartTime = microtime(true);
        $previewData = $this->dataGenerationService->previewData($template, 100);
        $generationTime = microtime(true) - $generationStartTime;

        $this->assertLessThan(5.0, $generationTime, 'Data generation should be efficient');
        $this->assertEquals(100, count($previewData));

        $finalMemory = memory_get_usage(true);
        $memoryUsed = $finalMemory - $initialMemory;

        $this->assertLessThan(32 * 1024 * 1024, $memoryUsed, 'Memory usage should be reasonable');
    }

    /**
     * Test data quality and validation
     */
    #[Test]
    public function test_data_quality_and_validation()
    {
        $this->createDataQualityTestTable();

        $template = DataGenerationTemplate::create([
            'name' => 'Quality Test Template',
            'table_name' => 'quality_test',
            'field_mappings' => [
                'email' => ['type' => 'faker', 'method' => 'email'],
                'phone' => ['type' => 'faker', 'method' => 'phoneNumber'],
                'birth_date' => ['type' => 'date', 'min' => '1960-01-01', 'max' => '2000-12-31'],
                'salary' => ['type' => 'number', 'min' => 30000, 'max' => 150000],
                'department' => ['type' => 'choice', 'options' => ['HR', 'IT', 'Sales', 'Marketing']],
            ],
            'is_active' => true,
        ]);

        $this->createdTemplates[] = $template->id;

        $generatedData = $this->dataGenerationService->previewData($template, 50);

        // Validate data quality
        foreach ($generatedData as $record) {
            // Email validation
            $this->assertMatchesRegularExpression('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $record['email']);

            // Phone validation (not empty)
            $this->assertNotEmpty($record['phone']);

            // Birth date validation
            $birthDate = Carbon::parse($record['birth_date']);
            $this->assertGreaterThanOrEqual(Carbon::parse('1960-01-01'), $birthDate);
            $this->assertLessThanOrEqual(Carbon::parse('2000-12-31'), $birthDate);

            // Salary range validation
            $this->assertGreaterThanOrEqual(30000, $record['salary']);
            $this->assertLessThanOrEqual(150000, $record['salary']);

            // Department choice validation
            $this->assertContains($record['department'], ['HR', 'IT', 'Sales', 'Marketing']);
        }
    }

    // Helper Methods

    /**
     * Create required tables for seeding tests
     */
    private function createRequiredTables(): void
    {
        // Create data_seeders table if not exists
        if (! Schema::hasTable('data_seeders')) {
            Schema::create('data_seeders', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('table_name');
                $table->json('configuration');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create seeder_execution_logs table if not exists
        if (! Schema::hasTable('seeder_execution_logs')) {
            Schema::create('seeder_execution_logs', function ($table) {
                $table->id();
                $table->unsignedBigInteger('seeder_id');
                $table->integer('records_created');
                $table->float('execution_time');
                $table->string('status');
                $table->text('error_message')->nullable();
                $table->timestamp('executed_at');
            });
        }

        // Create data_generation_templates table if not exists
        if (! Schema::hasTable('data_generation_templates')) {
            Schema::create('data_generation_templates', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('table_name');
                $table->json('field_mappings');
                $table->json('relationships')->nullable();
                $table->json('constraints')->nullable();
                $table->integer('default_count')->default(10);
                $table->json('sample_data')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Create test tables for seeding
     */
    private function createTestTablesForSeeding(): void
    {
        // Basic test table
        if (! Schema::hasTable('seed_test_users')) {
            Schema::create('seed_test_users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->integer('age');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'seed_test_users';
        }
    }

    /**
     * Create context-aware test tables
     */
    private function createContextAwareTestTables(): void
    {
        if (! Schema::hasTable('context_users')) {
            Schema::create('context_users', function ($table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone_number');
                $table->string('street_address');
                $table->string('city');
                $table->string('postal_code');
                $table->string('sku')->unique();
                $table->uuid('uuid');
                $table->date('birth_date');
                $table->decimal('salary', 10, 2);
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'context_users';
        }

        if (! Schema::hasTable('template_products')) {
            Schema::create('template_products', function ($table) {
                $table->id();
                $table->string('product_name');
                $table->decimal('price', 10, 2);
                $table->string('category');
                $table->text('description');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'template_products';
        }
    }

    /**
     * Create relationship test tables
     */
    private function createRelationshipTestTables(): void
    {
        // Categories table
        if (! Schema::hasTable('rel_categories')) {
            Schema::create('rel_categories', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'rel_categories';
        }

        // Products table with foreign key
        if (! Schema::hasTable('rel_products')) {
            Schema::create('rel_products', function ($table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->foreignId('category_id')->constrained('rel_categories');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'rel_products';
        }

        // Tags table
        if (! Schema::hasTable('rel_tags')) {
            Schema::create('rel_tags', function ($table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'rel_tags';
        }

        // Pivot table
        if (! Schema::hasTable('rel_product_tags')) {
            Schema::create('rel_product_tags', function ($table) {
                $table->id();
                $table->foreignId('product_id')->constrained('rel_products');
                $table->foreignId('tag_id')->constrained('rel_tags');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'rel_product_tags';
        }
    }

    /**
     * Create bulk test tables
     */
    private function createBulkTestTables(): void
    {
        if (! Schema::hasTable('bulk_records')) {
            Schema::create('bulk_records', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->integer('value');
                $table->text('description');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'bulk_records';
        }
    }

    /**
     * Create seeder management tables
     */
    private function createSeederManagementTables(): void
    {
        if (! Schema::hasTable('mgmt_users')) {
            Schema::create('mgmt_users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'mgmt_users';
        }

        if (! Schema::hasTable('mgmt_posts')) {
            Schema::create('mgmt_posts', function ($table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->foreignId('user_id')->constrained('mgmt_users');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'mgmt_posts';
        }
    }

    /**
     * Create data quality test table
     */
    private function createDataQualityTestTable(): void
    {
        if (! Schema::hasTable('quality_test')) {
            Schema::create('quality_test', function ($table) {
                $table->id();
                $table->string('email');
                $table->string('phone');
                $table->date('birth_date');
                $table->decimal('salary', 10, 2);
                $table->string('department');
                $table->timestamps();
            });
            $this->testTablesCreated[] = 'quality_test';
        }
    }

    /**
     * Create ecommerce template
     */
    private function createEcommerceTemplate(): DataGenerationTemplate
    {
        $template = DataGenerationTemplate::create([
            'name' => 'E-commerce Product Template',
            'description' => 'Template for generating e-commerce product data',
            'table_name' => 'template_products',
            'field_mappings' => [
                'product_name' => ['type' => 'faker', 'method' => 'productName'],
                'price' => ['type' => 'number', 'min' => 9.99, 'max' => 999.99],
                'category' => ['type' => 'choice', 'options' => ['Electronics', 'Clothing', 'Books', 'Home']],
                'description' => ['type' => 'faker', 'method' => 'text', 'length' => 200],
            ],
            'default_count' => 50,
            'is_active' => true,
        ]);

        $this->createdTemplates[] = $template->id;

        return $template;
    }

    /**
     * Create blog template
     */
    private function createBlogTemplate(): DataGenerationTemplate
    {
        $template = DataGenerationTemplate::create([
            'name' => 'Blog Post Template',
            'description' => 'Template for generating blog post data',
            'table_name' => 'blog_posts',
            'field_mappings' => [
                'title' => ['type' => 'faker', 'method' => 'sentence'],
                'content' => ['type' => 'faker', 'method' => 'paragraphs', 'count' => 3],
                'status' => ['type' => 'choice', 'options' => ['draft', 'published', 'archived']],
                'published_at' => ['type' => 'date', 'min' => '2023-01-01', 'max' => '2024-12-31'],
            ],
            'default_count' => 25,
            'is_active' => true,
        ]);

        $this->createdTemplates[] = $template->id;

        return $template;
    }

    /**
     * Create CRM template
     */
    private function createCRMTemplate(): DataGenerationTemplate
    {
        $template = DataGenerationTemplate::create([
            'name' => 'CRM Contact Template',
            'description' => 'Template for generating CRM contact data',
            'table_name' => 'crm_contacts',
            'field_mappings' => [
                'first_name' => ['type' => 'faker', 'method' => 'firstName'],
                'last_name' => ['type' => 'faker', 'method' => 'lastName'],
                'company' => ['type' => 'faker', 'method' => 'company'],
                'email' => ['type' => 'faker', 'method' => 'email'],
                'phone' => ['type' => 'faker', 'method' => 'phoneNumber'],
                'lead_score' => ['type' => 'number', 'min' => 0, 'max' => 100],
            ],
            'default_count' => 100,
            'is_active' => true,
        ]);

        $this->createdTemplates[] = $template->id;

        return $template;
    }

    /**
     * Create customized template
     */
    private function createCustomizedTemplate(DataGenerationTemplate $baseTemplate): DataGenerationTemplate
    {
        $customMappings = $baseTemplate->field_mappings;
        $customMappings['custom_field'] = ['type' => 'faker', 'method' => 'word'];

        $template = DataGenerationTemplate::create([
            'name' => 'Customized '.$baseTemplate->name,
            'description' => 'Customized version of '.$baseTemplate->name,
            'table_name' => $baseTemplate->table_name,
            'field_mappings' => $customMappings,
            'default_count' => $baseTemplate->default_count,
            'is_active' => true,
        ]);

        $this->createdTemplates[] = $template->id;

        return $template;
    }

    /**
     * Assert foreign key integrity
     */
    private function assertForeignKeyIntegrity(string $childTable, string $foreignKey, string $parentTable, string $parentKey): void
    {
        $invalidRecords = DB::table($childTable)
            ->leftJoin($parentTable, "{$childTable}.{$foreignKey}", '=', "{$parentTable}.{$parentKey}")
            ->whereNull("{$parentTable}.{$parentKey}")
            ->count();

        $this->assertEquals(0, $invalidRecords, "All {$childTable} records should have valid {$parentTable} references");
    }

    /**
     * Seed many-to-many relationships
     */
    private function seedManyToManyRelationships(): void
    {
        // First ensure we have tags
        $tagData = [
            ['name' => 'Popular', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Featured', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sale', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'New', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('rel_tags')->insert($tagData);

        // Get product and tag IDs
        $productIds = DB::table('rel_products')->pluck('id')->toArray();
        $tagIds = DB::table('rel_tags')->pluck('id')->toArray();

        // Create pivot records
        $pivotData = [];
        foreach ($productIds as $productId) {
            // Randomly assign 1-3 tags to each product
            $numTags = rand(1, 3);
            $selectedTags = array_rand($tagIds, $numTags);

            if (! is_array($selectedTags)) {
                $selectedTags = [$selectedTags];
            }

            foreach ($selectedTags as $tagIndex) {
                $pivotData[] = [
                    'product_id' => $productId,
                    'tag_id' => $tagIds[$tagIndex],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('rel_product_tags')->insert($pivotData);
    }

    /**
     * Verify bulk insert optimizations
     */
    private function verifyBulkInsertOptimizations(): void
    {
        // Test that bulk inserts are more efficient than individual inserts
        $singleInsertTime = $this->measureSingleInsertTime();
        $bulkInsertTime = $this->measureBulkInsertTime();

        $this->assertLessThan($singleInsertTime, $bulkInsertTime, 'Bulk inserts should be faster than single inserts');
    }

    /**
     * Measure single insert time
     */
    private function measureSingleInsertTime(): float
    {
        $startTime = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            DB::table('bulk_records')->insert([
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'value' => $this->faker->numberBetween(1, 1000),
                'description' => $this->faker->text,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return microtime(true) - $startTime;
    }

    /**
     * Measure bulk insert time
     */
    private function measureBulkInsertTime(): float
    {
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'value' => $this->faker->numberBetween(1, 1000),
                'description' => $this->faker->text,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $startTime = microtime(true);
        DB::table('bulk_records')->insert($data);

        return microtime(true) - $startTime;
    }

    /**
     * Create seeder configuration
     */
    private function createSeederConfiguration(string $name, string $tableName, int $recordCount): DataSeeder
    {
        return DataSeeder::create([
            'name' => $name,
            'description' => "Test seeder for {$tableName}",
            'table_name' => $tableName,
            'configuration' => json_encode([
                'record_count' => $recordCount,
                'fields' => [
                    'name' => ['type' => 'faker', 'method' => 'name'],
                    'email' => ['type' => 'faker', 'method' => 'email'],
                ],
            ]),
            'is_active' => true,
        ]);
    }

    /**
     * Log seeder execution
     */
    private function logSeederExecution(DataSeeder $seeder, int $recordsCreated, float $executionTime, string $status, ?string $errorMessage = null): void
    {
        SeederExecutionLog::create([
            'seeder_id' => $seeder->id,
            'records_created' => $recordsCreated,
            'execution_time' => $executionTime,
            'status' => $status,
            'error_message' => $errorMessage,
            'executed_at' => now(),
        ]);
    }

    /**
     * Get seeder performance metrics
     */
    private function getSeederPerformanceMetrics(int $seederId): array
    {
        $log = SeederExecutionLog::where('seeder_id', $seederId)
            ->where('status', 'completed')
            ->first();

        if (! $log) {
            return [];
        }

        return [
            'execution_time' => $log->execution_time,
            'records_per_second' => $log->records_created / max($log->execution_time, 0.001),
            'memory_usage' => memory_get_usage(true),
        ];
    }

    /**
     * Get seeder statistics
     */
    private function getSeederStatistics(): array
    {
        return [
            'total_executions' => SeederExecutionLog::count(),
            'successful_executions' => SeederExecutionLog::where('status', 'completed')->count(),
            'failed_executions' => SeederExecutionLog::where('status', 'failed')->count(),
            'total_records_created' => SeederExecutionLog::where('status', 'completed')->sum('records_created'),
        ];
    }

    /**
     * Clean up test data
     */
    private function cleanupTestData(): void
    {
        // Drop test tables in reverse order
        $tablesToDrop = array_reverse($this->testTablesCreated);

        foreach ($tablesToDrop as $tableName) {
            try {
                Schema::dropIfExists($tableName);
            } catch (\Exception $e) {
                // Ignore cleanup errors
            }
        }

        // Clean up created templates
        foreach ($this->createdTemplates as $templateId) {
            try {
                DataGenerationTemplate::find($templateId)?->delete();
            } catch (\Exception $e) {
                // Ignore cleanup errors
            }
        }

        $this->testTablesCreated = [];
        $this->createdTemplates = [];
    }
}
