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
        Schema::create('code_generation_histories', function (Blueprint $table) {
            $table->id();
            $table->string('generation_id')->unique();
            $table->string('type'); // migration, model, factory, seeder, policy, resource, controller, complete
            $table->string('file_name');
            $table->string('file_path');
            $table->string('class_name')->nullable();
            $table->string('namespace')->nullable();
            $table->json('configuration')->nullable();
            $table->longText('generated_code')->nullable();
            $table->string('template_used')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->integer('generation_time_ms')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('parent_generation_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['type', 'success']);
            $table->index(['created_at']);
            $table->index(['user_id']);
            $table->index(['parent_generation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_generation_histories');
    }
};
