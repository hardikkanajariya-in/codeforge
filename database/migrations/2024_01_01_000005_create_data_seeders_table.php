<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_seeders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('class_name');
            $table->string('file_path');
            $table->json('configuration')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->enum('type', ['laravel', 'generated', 'custom'])->default('laravel');
            $table->integer('priority')->default(100);
            $table->boolean('auto_run')->default(false);
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_seeders');
    }
};
