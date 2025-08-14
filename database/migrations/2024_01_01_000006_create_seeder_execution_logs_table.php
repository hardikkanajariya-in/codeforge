<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seeder_execution_logs', function (Blueprint $table) {
            $table->id();
            $table->string('seeder_name');
            $table->string('seeder_class');
            $table->enum('status', ['started', 'completed', 'failed'])->default('started');
            $table->integer('records_created')->default(0);
            $table->integer('records_updated')->default(0);
            $table->integer('records_failed')->default(0);
            $table->decimal('execution_time', 8, 3)->nullable(); // seconds with milliseconds
            $table->longText('output')->nullable();
            $table->longText('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->string('executed_by')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['seeder_name', 'started_at']);
            $table->index(['status', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seeder_execution_logs');
    }
};
