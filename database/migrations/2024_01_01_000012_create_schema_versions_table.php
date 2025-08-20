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
        if (!Schema::hasTable('schema_versions')) {
            Schema::create('schema_versions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('connection');
                $table->string('name');
                $table->text('description')->nullable();
                $table->longText('schema_data');
                $table->json('metadata')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('version_number')->default(1);
                $table->timestamps();

                $table->index(['user_id', 'connection']);
                $table->index(['connection', 'version_number']);
                $table->index(['user_id', 'is_active']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schema_versions');
    }
};
