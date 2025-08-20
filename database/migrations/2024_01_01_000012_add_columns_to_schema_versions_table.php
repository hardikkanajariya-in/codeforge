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
        Schema::table('schema_versions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->json('metadata')->nullable()->after('schema_data');
            $table->boolean('is_active')->default(true)->after('metadata');
            $table->integer('version_number')->default(1)->after('is_active');

            // Add new indexes
            $table->index(['connection', 'version_number']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schema_versions', function (Blueprint $table) {
            $table->dropIndex(['connection', 'version_number']);
            $table->dropIndex(['user_id', 'is_active']);

            $table->dropColumn(['description', 'metadata', 'is_active', 'version_number']);
        });
    }
};
