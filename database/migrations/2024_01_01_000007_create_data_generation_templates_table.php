<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_generation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('table_name');
            $table->json('field_mappings'); // Maps table columns to faker/generators
            $table->json('relationships')->nullable(); // Defines relationships with other tables
            $table->json('constraints')->nullable(); // Business rules and constraints
            $table->integer('default_count')->default(10);
            $table->json('sample_data')->nullable(); // Sample generated data for preview
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['table_name', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_generation_templates');
    }
};
