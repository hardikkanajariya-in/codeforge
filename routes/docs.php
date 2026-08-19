<?php

use HkDevs\CodeForgeStudio\Http\Controllers\DocsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CodeForge Database Studio Documentation Routes
|--------------------------------------------------------------------------
|
| These routes handle the documentation system for developers who want
| to understand the plugin's architecture, features, and implementation.
| All routes are prefixed with 'codeforge/docs' and named with 'codeforge.docs.'
|
*/

Route::middleware(['web'])->prefix('codeforge/docs')->name('codeforge.docs.')->group(function () {

    // Home/Getting Started
    Route::get('/', [DocsController::class, 'home'])->name('home');
    Route::get('/getting-started', [DocsController::class, 'gettingStarted'])->name('getting-started');

    // Installation & Configuration
    Route::get('/installation', [DocsController::class, 'installation'])->name('installation');
    Route::get('/configuration', [DocsController::class, 'configuration'])->name('configuration');
    Route::get('/requirements', [DocsController::class, 'requirements'])->name('requirements');

    // Core Features Documentation
    Route::get('/features/overview', [DocsController::class, 'featuresOverview'])->name('features.overview');
    Route::get('/features/database-health', [DocsController::class, 'databaseHealth'])->name('features.database-health');
    Route::get('/features/migration-management', [DocsController::class, 'migrationManagement'])->name('features.migration-management');
    Route::get('/features/schema-designer', [DocsController::class, 'schemaDesigner'])->name('features.schema-designer');
    Route::get('/features/code-generation', [DocsController::class, 'codeGeneration'])->name('features.code-generation');
    Route::get('/features/data-seeding', [DocsController::class, 'dataSeeding'])->name('features.data-seeding');
    Route::get('/features/documentation-generator', [DocsController::class, 'documentationGenerator'])->name('features.documentation-generator');

    // Architecture & Development
    Route::get('/architecture/overview', [DocsController::class, 'architectureOverview'])->name('architecture.overview');
    Route::get('/architecture/services', [DocsController::class, 'services'])->name('architecture.services');
    Route::get('/architecture/events', [DocsController::class, 'events'])->name('architecture.events');
    Route::get('/architecture/database-design', [DocsController::class, 'databaseDesign'])->name('architecture.database-design');
    Route::get('/architecture/security', [DocsController::class, 'security'])->name('architecture.security');

    // API Reference
    Route::get('/api/overview', [DocsController::class, 'apiOverview'])->name('api.overview');
    Route::get('/api/services', [DocsController::class, 'apiServices'])->name('api.services');
    Route::get('/api/commands', [DocsController::class, 'apiCommands'])->name('api.commands');
    Route::get('/api/filament-resources', [DocsController::class, 'apiFilamentResources'])->name('api.filament-resources');

    // Advanced Topics
    Route::get('/advanced/customization', [DocsController::class, 'customization'])->name('advanced.customization');
    Route::get('/advanced/extending', [DocsController::class, 'extending'])->name('advanced.extending');
    Route::get('/advanced/performance', [DocsController::class, 'performance'])->name('advanced.performance');
    Route::get('/advanced/testing', [DocsController::class, 'testing'])->name('advanced.testing');
    Route::get('/advanced/deployment', [DocsController::class, 'deployment'])->name('advanced.deployment');

    // Development Guidelines
    Route::get('/guidelines/coding-standards', [DocsController::class, 'codingStandards'])->name('guidelines.coding-standards');
    Route::get('/guidelines/contribution', [DocsController::class, 'contribution'])->name('guidelines.contribution');
    Route::get('/guidelines/workflow', [DocsController::class, 'workflow'])->name('guidelines.workflow');

    // Troubleshooting & Support
    Route::get('/troubleshooting', [DocsController::class, 'troubleshooting'])->name('troubleshooting');
    Route::get('/faq', [DocsController::class, 'faq'])->name('faq');
    Route::get('/changelog', [DocsController::class, 'changelog'])->name('changelog');
    Route::get('/support', [DocsController::class, 'support'])->name('support');

    // Search endpoint for documentation
    Route::get('/search', [DocsController::class, 'search'])->name('search');

    // Redirect legacy routes
    Route::get('/api-reference', function () {
        return redirect()->route('codeforge.docs.api.overview');
    });
    Route::get('/api', function () {
        return redirect()->route('codeforge.docs.api.overview');
    });
});
