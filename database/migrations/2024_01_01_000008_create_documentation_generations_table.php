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
        Schema::create('documentation_generations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('version')->default('1.0.0');
            $table->enum('format', ['markdown', 'html', 'pdf'])->default('markdown');
            $table->enum('scope', ['full_schema', 'selected_tables', 'single_table', 'models_only'])->default('full_schema');
            $table->json('included_tables')->nullable(); // Array of table names when scope is selective
            $table->json('options')->nullable(); // Additional generation options
            $table->text('file_path')->nullable(); // Where the generated file is stored
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->json('metadata')->nullable(); // Schema metadata at generation time
            $table->enum('status', ['pending', 'generating', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->string('generated_by')->nullable(); // User who generated
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['scope', 'format']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_generations');
    }
};
