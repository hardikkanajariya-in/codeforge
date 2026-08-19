<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use HkDevs\CodeForgeStudio\Services\IntelligentSuggestionService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * IntelligentSuggestionServiceTest
 * 
 * Tests for the intelligent suggestion engine that replaces static suggestions
 * with dynamic database-driven analysis for model generation.
 */
class IntelligentSuggestionServiceTest extends TestCase
{
    protected IntelligentSuggestionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new IntelligentSuggestionService();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_fallback_suggestions_when_table_does_not_exist()
    {
        $suggestions = $this->service->getModelSuggestions('NonExistentModel');

        $this->assertIsArray($suggestions);
        $this->assertArrayHasKey('fillable', $suggestions);
        $this->assertArrayHasKey('relations', $suggestions);
        $this->assertArrayHasKey('casts', $suggestions);

        // Should provide minimal sensible defaults
        $this->assertContains('name', $suggestions['fillable']);
        $this->assertEmpty($suggestions['relations']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_model_suggestions()
    {
        // Create a users table for testing
        Schema::create('test_users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $suggestions = $this->service->getModelSuggestions('User', 'test_users');

        // Should suggest appropriate fillable fields
        $this->assertContains('name', $suggestions['fillable']);
        $this->assertContains('email', $suggestions['fillable']);
        $this->assertNotContains('password', $suggestions['fillable']); // Security sensitive

        // Should suggest hidden fields
        $this->assertContains('password', $suggestions['hidden']);
        $this->assertContains('remember_token', $suggestions['hidden']);

        // Should suggest appropriate traits
        $this->assertContains('HasFactory', $suggestions['traits']);
        $this->assertContains('Notifiable', $suggestions['traits']);

        Schema::dropIfExists('test_users');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_product_model_with_relationships()
    {
        // Create categories table first
        Schema::create('test_categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create products table with foreign key
        Schema::create('test_products', function ($table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('sku')->unique();
            $table->foreignId('category_id')->constrained('test_categories');
            $table->boolean('is_active')->default(true);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();
        });

        $suggestions = $this->service->getModelSuggestions('Product', 'test_products');

        // Should suggest appropriate fillable fields
        $fillable = $suggestions['fillable'];
        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('sku', $fillable);
        $this->assertContains('category_id', $fillable);
        $this->assertContains('is_active', $fillable);
        $this->assertContains('stock_quantity', $fillable);

        // Should suggest appropriate casts
        $casts = collect($suggestions['casts']);
        $this->assertTrue($casts->contains('attribute', 'price'));
        $this->assertTrue($casts->contains('attribute', 'is_active'));
        $this->assertTrue($casts->contains('attribute', 'stock_quantity'));

        // Should discover relationship
        $relations = $suggestions['relations'];
        $this->assertNotEmpty($relations);
        $categoryRelation = collect($relations)->firstWhere('name', 'category');
        $this->assertNotNull($categoryRelation);
        $this->assertEquals('belongsTo', $categoryRelation['type']);
        $this->assertEquals('TestCategory', $categoryRelation['related_model']);

        Schema::dropIfExists('test_products');
        Schema::dropIfExists('test_categories');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_casting_suggestions_intelligence()
    {
        Schema::create('test_smart_casts', function ($table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active');
            $table->json('settings');
            $table->timestamp('published_at')->nullable();
            $table->float('rating');
            $table->integer('view_count');
            $table->timestamps();
        });

        $suggestions = $this->service->getCastingSuggestions('SmartCast', 'test_smart_casts');

        $castMap = collect($suggestions)->pluck('cast', 'attribute')->toArray();

        $this->assertEquals('decimal:2', $castMap['price']);
        $this->assertEquals('boolean', $castMap['is_active']);
        $this->assertEquals('array', $castMap['settings']);
        $this->assertEquals('datetime', $castMap['published_at']);
        $this->assertEquals('float', $castMap['rating']);
        $this->assertEquals('integer', $castMap['view_count']);

        Schema::dropIfExists('test_smart_casts');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_pattern_based_suggestions()
    {
        $suggestions = $this->service->getModelSuggestions('BlogPost');

        // Should recognize blog/post pattern and suggest appropriate fields
        $this->assertIsArray($suggestions['fillable']);

        // Even without table, should provide intelligent fallback
        $this->assertContains('name', $suggestions['fillable']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_hidden_field_detection()
    {
        Schema::create('test_security', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('public_data');
            $table->timestamps();
        });

        $hidden = $this->service->getHiddenFieldSuggestions('Security', 'test_security');

        $this->assertContains('password', $hidden);
        $this->assertContains('api_token', $hidden);
        $this->assertContains('secret_key', $hidden);
        $this->assertNotContains('name', $hidden);
        $this->assertNotContains('public_data', $hidden);

        Schema::dropIfExists('test_security');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_trait_suggestions_based_on_table_structure()
    {
        Schema::create('test_traits', function ($table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        $traits = $this->service->getTraitSuggestions('TestModel', 'test_traits');

        $this->assertContains('HasFactory', $traits);
        $this->assertContains('SoftDeletes', $traits);
        $this->assertContains('HasUuids', $traits);

        Schema::dropIfExists('test_traits');
    }
}
