<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('migration_histories', function (Blueprint $table) {
            $table->id();
            $table->string('migration');
            $table->integer('batch')->nullable();
            $table->enum('action', ['migrate', 'rollback', 'refresh', 'reset'])->default('migrate');
            $table->string('executed_by')->nullable();
            $table->float('execution_time')->nullable(); // in seconds
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('error_message')->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();
            
            $table->index(['migration', 'action']);
            $table->index('executed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('migration_histories');
    }
};