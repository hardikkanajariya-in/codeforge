<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('query_performance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('connection', 100)->default('default');
            $table->text('query'); // The SQL query
            $table->string('query_hash', 191)->nullable(); // Hash of the query for grouping (191 chars = 764 bytes in utf8mb4)
            $table->decimal('execution_time', 10, 4); // In milliseconds
            $table->integer('rows_affected')->nullable();
            $table->json('bindings')->nullable(); // Query bindings
            $table->string('type', 50)->nullable(); // 'select', 'insert', 'update', 'delete'
            $table->string('status', 20)->default('success'); // 'success', 'error'
            $table->text('error_message')->nullable();
            $table->string('user_id', 100)->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();

            $table->index(['connection', 'executed_at']);
            $table->index(['query_hash', 'executed_at']);
            $table->index('execution_time');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_performance_logs');
    }
};
