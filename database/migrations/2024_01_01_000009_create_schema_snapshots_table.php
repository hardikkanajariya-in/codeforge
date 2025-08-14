<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schema_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('version')->default('1.0.0');
            $table->string('database_connection')->default('mysql');
            $table->json('schema_data'); // Complete schema structure
            $table->json('table_relationships'); // Foreign key relationships
            $table->json('model_mappings')->nullable(); // Eloquent model to table mappings
            $table->json('validation_rules')->nullable(); // Laravel validation rules extracted from models
            $table->json('policy_information')->nullable(); // Model policies information
            $table->integer('tables_count')->default(0);
            $table->integer('relationships_count')->default(0);
            $table->integer('models_count')->default(0);
            $table->string('hash')->nullable(); // Hash of schema data for change detection
            $table->boolean('is_baseline')->default(false); // Mark as baseline snapshot
            $table->timestamp('captured_at');
            $table->string('captured_by')->nullable();
            $table->timestamps();

            $table->index(['hash']);
            $table->index(['is_baseline', 'captured_at']);
            $table->index(['database_connection', 'captured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schema_snapshots');
    }
};
