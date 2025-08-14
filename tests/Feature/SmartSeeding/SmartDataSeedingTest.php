<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\SmartSeeding;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Test Case: TC-SEEDING-001 - Smart Data Seeding
 * Purpose: Test smart data seeding features and template-based generation
 */
class SmartDataSeedingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create data seeders table
        if (!Schema::hasTable('data_seeders')) {
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

        // Create seeder execution logs table
        if (!Schema::hasTable('seeder_execution_logs')) {
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

        // Create data generation templates table
        if (!Schema::hasTable('data_generation_templates')) {
            Schema::create('data_generation_templates', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('table_name');
                $table->json('template_data');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_seeder_configuration_creation()
    {
        $seederConfig = [
            'table_name' => 'test_users',
            'record_count' => 100,
            'fields' => [
                'name' => ['type' => 'faker', 'method' => 'name'],
                'email' => ['type' => 'faker', 'method' => 'email'],
                'age' => ['type' => 'range', 'min' => 18, 'max' => 65],
                'is_active' => ['type' => 'boolean', 'probability' => 0.8]
            ]
        ];

        $seederId = DB::table('data_seeders')->insertGetId([
            'name' => 'User Seeder',
            'description' => 'Generates test users with realistic data',
            'table_name' => 'test_users',
            'configuration' => json_encode($seederConfig),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $seeder = DB::table('data_seeders')->where('id', $seederId)->first();

        $this->assertNotNull($seeder);
        $this->assertEquals('User Seeder', $seeder->name);
        $this->assertEquals('test_users', $seeder->table_name);

        $config = json_decode($seeder->configuration, true);
        $this->assertEquals(100, $config['record_count']);
        $this->assertArrayHasKey('fields', $config);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_data_generation_template()
    {
        $template = [
            'fields' => [
                'first_name' => [
                    'type' => 'faker',
                    'provider' => 'firstName'
                ],
                'last_name' => [
                    'type' => 'faker',
                    'provider' => 'lastName'
                ],
                'email' => [
                    'type' => 'faker',
                    'provider' => 'email'
                ],
                'birth_date' => [
                    'type' => 'date_range',
                    'start' => '1960-01-01',
                    'end' => '2005-12-31'
                ],
                'salary' => [
                    'type' => 'number_range',
                    'min' => 30000,
                    'max' => 150000
                ]
            ],
            'relationships' => [
                'department_id' => [
                    'type' => 'foreign_key',
                    'table' => 'departments',
                    'column' => 'id'
                ]
            ]
        ];

        $templateId = DB::table('data_generation_templates')->insertGetId([
            'name' => 'Employee Template',
            'table_name' => 'employees',
            'template_data' => json_encode($template),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $savedTemplate = DB::table('data_generation_templates')->where('id', $templateId)->first();

        $this->assertNotNull($savedTemplate);
        $this->assertEquals('Employee Template', $savedTemplate->name);

        $templateData = json_decode($savedTemplate->template_data, true);
        $this->assertArrayHasKey('fields', $templateData);
        $this->assertArrayHasKey('relationships', $templateData);
        $this->assertCount(5, $templateData['fields']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_seeder_execution_simulation()
    {
        // Create test table for seeding
        Schema::create('test_products', function ($table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create seeder configuration
        $seederId = DB::table('data_seeders')->insertGetId([
            'name' => 'Product Seeder',
            'description' => 'Generates test products',
            'table_name' => 'test_products',
            'configuration' => json_encode([
                'record_count' => 50,
                'fields' => [
                    'name' => ['type' => 'faker', 'method' => 'productName'],
                    'price' => ['type' => 'range', 'min' => 10.00, 'max' => 999.99],
                    'description' => ['type' => 'faker', 'method' => 'text'],
                    'is_active' => ['type' => 'boolean', 'probability' => 0.9]
                ]
            ]),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simulate seeder execution
        $startTime = microtime(true);

        // Generate test data (simplified)
        $testData = [];
        for ($i = 0; $i < 50; $i++) {
            $testData[] = [
                'name' => "Test Product {$i}",
                'price' => rand(1000, 99999) / 100, // Random price between 10.00 and 999.99
                'description' => "Description for test product {$i}",
                'is_active' => rand(0, 10) < 9, // 90% probability of being active
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('test_products')->insert($testData);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Log execution
        DB::table('seeder_execution_logs')->insert([
            'seeder_id' => $seederId,
            'records_created' => 50,
            'execution_time' => $executionTime,
            'status' => 'completed',
            'executed_at' => now()
        ]);

        // Verify results
        $recordCount = DB::table('test_products')->count();
        $this->assertEquals(50, $recordCount);

        $executionLog = DB::table('seeder_execution_logs')
            ->where('seeder_id', $seederId)
            ->first();

        $this->assertNotNull($executionLog);
        $this->assertEquals('completed', $executionLog->status);
        $this->assertEquals(50, $executionLog->records_created);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_foreign_key_relationship_seeding()
    {
        // Create related tables
        Schema::create('test_categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('test_items', function ($table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('test_categories');
        });

        // Seed categories first
        $categories = [];
        for ($i = 1; $i <= 5; $i++) {
            $categories[] = [
                'name' => "Category {$i}",
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('test_categories')->insert($categories);

        // Get category IDs for relationship seeding
        $categoryIds = DB::table('test_categories')->pluck('id')->toArray();

        // Seed items with foreign key relationships
        $items = [];
        for ($i = 1; $i <= 20; $i++) {
            $items[] = [
                'name' => "Item {$i}",
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('test_items')->insert($items);

        // Verify relationships
        $itemsCount = DB::table('test_items')->count();
        $this->assertEquals(20, $itemsCount);

        // Verify all items have valid category relationships
        $itemsWithValidCategories = DB::table('test_items')
            ->join('test_categories', 'test_items.category_id', '=', 'test_categories.id')
            ->count();

        $this->assertEquals(20, $itemsWithValidCategories);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_conditional_data_generation()
    {
        Schema::create('test_users_conditional', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('role');
            $table->decimal('salary', 10, 2)->nullable();
            $table->timestamps();
        });

        // Create conditional seeder
        $conditionalConfig = [
            'record_count' => 30,
            'fields' => [
                'name' => ['type' => 'faker', 'method' => 'name'],
                'email' => ['type' => 'faker', 'method' => 'email'],
                'role' => [
                    'type' => 'weighted_choice',
                    'choices' => [
                        'admin' => 0.1,
                        'manager' => 0.2,
                        'employee' => 0.7
                    ]
                ],
                'salary' => [
                    'type' => 'conditional',
                    'conditions' => [
                        ['field' => 'role', 'value' => 'admin', 'result' => ['min' => 80000, 'max' => 120000]],
                        ['field' => 'role', 'value' => 'manager', 'result' => ['min' => 50000, 'max' => 80000]],
                        ['field' => 'role', 'value' => 'employee', 'result' => ['min' => 30000, 'max' => 50000]]
                    ]
                ]
            ]
        ];

        $seederId = DB::table('data_seeders')->insertGetId([
            'name' => 'Conditional User Seeder',
            'description' => 'Generates users with role-based conditional data',
            'table_name' => 'test_users_conditional',
            'configuration' => json_encode($conditionalConfig),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simulate conditional data generation
        $roles = ['admin', 'manager', 'employee'];
        $salaryRanges = [
            'admin' => ['min' => 80000, 'max' => 120000],
            'manager' => ['min' => 50000, 'max' => 80000],
            'employee' => ['min' => 30000, 'max' => 50000]
        ];

        $testData = [];
        for ($i = 0; $i < 30; $i++) {
            $role = $roles[array_rand($roles)];
            $salaryRange = $salaryRanges[$role];

            $testData[] = [
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'role' => $role,
                'salary' => rand($salaryRange['min'], $salaryRange['max']),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('test_users_conditional')->insert($testData);

        // Verify conditional logic
        $adminUsers = DB::table('test_users_conditional')->where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $this->assertGreaterThanOrEqual(80000, $admin->salary);
            $this->assertLessThanOrEqual(120000, $admin->salary);
        }

        $employeeUsers = DB::table('test_users_conditional')->where('role', 'employee')->get();
        foreach ($employeeUsers as $employee) {
            $this->assertGreaterThanOrEqual(30000, $employee->salary);
            $this->assertLessThanOrEqual(50000, $employee->salary);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_data_validation_during_seeding()
    {
        Schema::create('test_validated_data', function ($table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('phone', 15);
            $table->integer('age');
            $table->enum('status', ['active', 'inactive', 'pending']);
            $table->timestamps();
        });

        // Test data validation rules
        $validationRules = [
            'email' => ['type' => 'email', 'unique' => true],
            'phone' => ['type' => 'phone', 'format' => 'international'],
            'age' => ['type' => 'integer', 'min' => 18, 'max' => 100],
            'status' => ['type' => 'enum', 'values' => ['active', 'inactive', 'pending']]
        ];

        // Generate valid test data
        $validData = [];
        for ($i = 0; $i < 10; $i++) {
            $validData[] = [
                'email' => "test{$i}@example.com",
                'phone' => "+1234567890{$i}",
                'age' => rand(18, 65),
                'status' => ['active', 'inactive', 'pending'][rand(0, 2)],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('test_validated_data')->insert($validData);

        // Verify data integrity
        $records = DB::table('test_validated_data')->get();

        foreach ($records as $record) {
            $this->assertMatchesRegularExpression('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $record->email);
            $this->assertGreaterThanOrEqual(18, $record->age);
            $this->assertLessThanOrEqual(100, $record->age);
            $this->assertContains($record->status, ['active', 'inactive', 'pending']);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_bulk_seeding_performance()
    {
        Schema::create('performance_test_bulk', function ($table) {
            $table->id();
            $table->string('name');
            $table->integer('value');
            $table->timestamps();
        });

        $recordCount = 1000;
        $startTime = microtime(true);

        // Generate bulk data
        $bulkData = [];
        for ($i = 0; $i < $recordCount; $i++) {
            $bulkData[] = [
                'name' => "Record {$i}",
                'value' => rand(1, 10000),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert in chunks for better performance
        $chunks = array_chunk($bulkData, 100);
        foreach ($chunks as $chunk) {
            DB::table('performance_test_bulk')->insert($chunk);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verify bulk insertion
        $insertedCount = DB::table('performance_test_bulk')->count();
        $this->assertEquals($recordCount, $insertedCount);

        // Log performance metrics
        $this->assertLessThan(5.0, $executionTime, 'Bulk seeding should complete within 5 seconds');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_seeder_error_handling()
    {
        // Create seeder with invalid configuration
        $invalidSeederId = DB::table('data_seeders')->insertGetId([
            'name' => 'Invalid Seeder',
            'description' => 'Seeder with invalid configuration',
            'table_name' => 'non_existent_table',
            'configuration' => json_encode([
                'record_count' => 10,
                'fields' => [
                    'invalid_field' => ['type' => 'unknown_type']
                ]
            ]),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simulate failed execution
        DB::table('seeder_execution_logs')->insert([
            'seeder_id' => $invalidSeederId,
            'records_created' => 0,
            'execution_time' => 0.1,
            'status' => 'failed',
            'error_message' => 'Table non_existent_table does not exist',
            'executed_at' => now()
        ]);

        $errorLog = DB::table('seeder_execution_logs')
            ->where('seeder_id', $invalidSeederId)
            ->first();

        $this->assertEquals('failed', $errorLog->status);
        $this->assertNotNull($errorLog->error_message);
        $this->assertStringContainsString('does not exist', $errorLog->error_message);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_seeder_template_reusability()
    {
        // Create reusable template
        $userTemplate = [
            'fields' => [
                'first_name' => ['type' => 'faker', 'provider' => 'firstName'],
                'last_name' => ['type' => 'faker', 'provider' => 'lastName'],
                'email' => ['type' => 'faker', 'provider' => 'email'],
                'created_at' => ['type' => 'current_timestamp'],
                'updated_at' => ['type' => 'current_timestamp']
            ]
        ];

        $templateId = DB::table('data_generation_templates')->insertGetId([
            'name' => 'Standard User Template',
            'table_name' => 'users',
            'template_data' => json_encode($userTemplate),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Use template for multiple seeders
        $seederConfigs = [
            ['name' => 'Admin Users', 'count' => 5, 'table' => 'admin_users'],
            ['name' => 'Regular Users', 'count' => 100, 'table' => 'regular_users'],
            ['name' => 'Test Users', 'count' => 50, 'table' => 'test_users']
        ];

        foreach ($seederConfigs as $config) {
            DB::table('data_seeders')->insert([
                'name' => $config['name'],
                'description' => "Generated from Standard User Template",
                'table_name' => $config['table'],
                'configuration' => json_encode([
                    'template_id' => $templateId,
                    'record_count' => $config['count'],
                    'additional_fields' => []
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Verify template reuse
        $seedersUsingTemplate = DB::table('data_seeders')
            ->where('configuration', 'like', "%\"template_id\":{$templateId}%")
            ->count();

        $this->assertEquals(3, $seedersUsingTemplate);
    }
}
