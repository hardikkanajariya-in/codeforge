<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('database_manager_logs', function (Blueprint $table) {
            $table->id();
            $table->string('connection', 100)->default('default');
            $table->string('action', 100); // 'migration_run', 'migration_rollback', 'schema_change', etc.
            $table->string('type', 50)->nullable(); // 'info', 'warning', 'error', 'success'
            $table->text('description');
            $table->json('metadata')->nullable(); // Additional data about the action
            $table->string('user_id', 100)->nullable(); // Who performed the action
            $table->timestamp('executed_at');
            $table->timestamps();

            $table->index(['connection', 'action']);
            $table->index('executed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_manager_logs');
    }
};
