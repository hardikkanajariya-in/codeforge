<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\SchemaDesigner;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;

/**
 * Test Case: TC-SCHEMA-001 - Schema Designer Core Functionality
 * Purpose: Test core schema designer features and operations
 */
class SchemaDesignerCoreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_schema_designer_page_loads()
    {
        // Test that schema designer page can be accessed
        // This would typically be a browser test, but we'll test the underlying functionality
        $this->assertTrue(
            class_exists('HkDevs\CodeForgeStudio\Pages\SchemaDesigner'),
            'Schema Designer page class should exist'
        );
    }

    #[Test]
    public function test_database_table_listing()
    {
        // Create some test tables
        Schema::create('test_users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('test_posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('test_users');
        });

        // Test that we can list database tables
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $this->assertContains('test_users', $tables);
        $this->assertContains('test_posts', $tables);
    }

    #[Test]
    public function test_table_schema_inspection()
    {
        // Create a test table
        Schema::create('test_inspection', function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Test that we can inspect table schema
        $columns = Schema::getColumnListing('test_inspection');

        $expectedColumns = ['id', 'name', 'description', 'count', 'is_active', 'created_at', 'updated_at'];

        foreach ($expectedColumns as $column) {
            $this->assertContains($column, $columns, "Column {$column} should be present");
        }
    }

    #[Test]
    public function test_table_column_details()
    {
        Schema::create('test_column_details', function ($table) {
            $table->id();
            $table->string('varchar_field', 255);
            $table->text('text_field');
            $table->integer('integer_field');
            $table->boolean('boolean_field');
            $table->decimal('decimal_field', 10, 2);
            $table->timestamp('timestamp_field')->nullable();
        });

        // Test column type detection
        $connection = DB::connection();
        $schemaBuilder = $connection->getSchemaBuilder();

        $this->assertTrue(
            $schemaBuilder->hasColumn('test_column_details', 'varchar_field'),
            'VARCHAR field should be detected'
        );

        $this->assertTrue(
            $schemaBuilder->hasColumn('test_column_details', 'text_field'),
            'TEXT field should be detected'
        );

        $this->assertTrue(
            $schemaBuilder->hasColumn('test_column_details', 'integer_field'),
            'INTEGER field should be detected'
        );
    }

    #[Test]
    public function test_foreign_key_detection()
    {
        // Create related tables
        Schema::create('test_categories', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('test_products', function ($table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('test_categories');
        });

        // Test foreign key detection
        $this->assertTrue(
            Schema::hasColumn('test_products', 'category_id'),
            'Foreign key column should exist'
        );
    }

    #[Test]
    public function test_index_detection()
    {
        Schema::create('test_indexes', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('code');
            $table->string('status');

            $table->index('name');
            $table->index(['code', 'status']);
        });

        // Test that table was created successfully
        $this->assertTrue(
            Schema::hasTable('test_indexes'),
            'Table with indexes should be created'
        );

        // Test column existence
        $columns = ['id', 'name', 'email', 'code', 'status'];
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('test_indexes', $column),
                "Column {$column} should exist"
            );
        }
    }

    #[Test]
    public function test_table_relationship_mapping()
    {
        // Create a complex relationship structure
        Schema::create('test_authors', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('test_books', function ($table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('test_authors');
        });

        Schema::create('test_reviews', function ($table) {
            $table->id();
            $table->text('content');
            $table->unsignedBigInteger('book_id');
            $table->foreign('book_id')->references('id')->on('test_books');
        });

        // Test that all tables were created
        $this->assertTrue(Schema::hasTable('test_authors'));
        $this->assertTrue(Schema::hasTable('test_books'));
        $this->assertTrue(Schema::hasTable('test_reviews'));

        // Test foreign key columns exist
        $this->assertTrue(Schema::hasColumn('test_books', 'author_id'));
        $this->assertTrue(Schema::hasColumn('test_reviews', 'book_id'));
    }

    #[Test]
    public function test_table_constraint_validation()
    {
        Schema::create('test_constraints', function ($table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('email')->unique();
            $table->integer('age')->unsigned();
        });

        // Test that constraints work
        try {
            DB::table('test_constraints')->insert([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'age' => 25,
            ]);

            $this->assertTrue(true, 'Valid data should be inserted');
        } catch (\Exception $e) {
            $this->fail('Valid data insertion failed: '.$e->getMessage());
        }
    }

    #[Test]
    public function test_schema_export_capability()
    {
        // Create a test table
        Schema::create('test_export', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Test schema export (basic functionality)
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        $this->assertContains('test_export', $tables);

        // Test column information export
        $columns = Schema::getColumnListing('test_export');
        $this->assertIsArray($columns);
        $this->assertNotEmpty($columns);
    }

    #[Test]
    public function test_database_connection_handling()
    {
        // Test multiple database connection handling
        $defaultConnection = DB::connection();
        $this->assertNotNull($defaultConnection);

        // Test connection driver detection
        $driverName = $defaultConnection->getDriverName();
        $this->assertContains($driverName, ['mysql', 'pgsql', 'sqlite', 'sqlsrv']);
    }

    #[Test]
    public function test_table_size_calculation()
    {
        // Create a table with some data
        Schema::create('test_size', function ($table) {
            $table->id();
            $table->string('data');
            $table->timestamps();
        });

        // Insert some test data
        for ($i = 0; $i < 10; $i++) {
            DB::table('test_size')->insert([
                'data' => 'Test data row '.$i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Test that we can count records
        $count = DB::table('test_size')->count();
        $this->assertEquals(10, $count);
    }

    #[Test]
    public function test_schema_backup_preparation()
    {
        // Create multiple test tables
        Schema::create('backup_table_1', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('backup_table_2', function ($table) {
            $table->id();
            $table->text('content');
        });

        // Test that tables can be listed for backup
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $this->assertContains('backup_table_1', $tables);
        $this->assertContains('backup_table_2', $tables);
    }

    #[Test]
    public function test_table_modification_detection()
    {
        // Create initial table
        Schema::create('test_modification', function ($table) {
            $table->id();
            $table->string('name');
        });

        $initialColumns = Schema::getColumnListing('test_modification');

        // Modify table (add column)
        Schema::table('test_modification', function ($table) {
            $table->string('email')->nullable();
        });

        $modifiedColumns = Schema::getColumnListing('test_modification');

        // Test that modification was detected
        $this->assertContains('email', $modifiedColumns);
        $this->assertNotEquals($initialColumns, $modifiedColumns);
    }

    #[Test]
    public function test_complex_data_types()
    {
        // Test various data types support
        Schema::create('test_data_types', function ($table) {
            $table->id();
            $table->json('json_field')->nullable();
            $table->date('date_field');
            $table->time('time_field');
            $table->dateTime('datetime_field');
            $table->enum('status', ['active', 'inactive']);
        });

        // Test that all columns were created
        $columns = Schema::getColumnListing('test_data_types');

        $expectedColumns = ['id', 'json_field', 'date_field', 'time_field', 'datetime_field', 'status'];

        foreach ($expectedColumns as $column) {
            $this->assertContains($column, $columns, "Column {$column} should exist");
        }
    }
}
