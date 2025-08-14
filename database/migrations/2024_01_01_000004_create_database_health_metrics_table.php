<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('database_health_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('connection', 100)->default('default');
            $table->string('metric_type', 100); // 'connection_status', 'response_time', 'active_connections', etc.
            $table->string('metric_name', 100);
            $table->decimal('value', 15, 4);
            $table->string('unit', 20)->nullable(); // 'ms', 'count', '%', 'MB', etc.
            $table->string('status', 20)->default('normal'); // 'normal', 'warning', 'critical'
            $table->json('metadata')->nullable(); // Additional metric data
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['connection', 'metric_type', 'recorded_at']);
            $table->index(['metric_name', 'recorded_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_health_metrics');
    }
};
