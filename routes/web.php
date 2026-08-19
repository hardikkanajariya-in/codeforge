<?php

use HkDevs\CodeForgeStudio\Http\Controllers\DocumentationDownloadController;
use HkDevs\CodeForgeStudio\Services\AssetService;
use Illuminate\Support\Facades\Route;

// Asset serving route with fallback to package directory
Route::middleware('web')->get('/codeforge/assets/{path}', function ($path) {
    return AssetService::serveAsset($path);
})->where('path', '.*')->name('codeforge.asset');

// Schema Export Download Route
Route::middleware('web')->get('/schema/download/{file}', function ($file) {
    $filePath = storage_path('app/temp/'.$file);

    if (! file_exists($filePath)) {
        abort(404, 'File not found');
    }

    return response()->download($filePath, $file, [
        'Content-Type' => 'application/json',
    ])->deleteFileAfterSend(true);
})->name('schema.download');

Route::middleware('web')->prefix('admin/database-manager/documentation')->name('admin.database-manager.documentation.')->group(function () {
    Route::get('/{generation}/download', [DocumentationDownloadController::class, 'download'])
        ->name('download');

    Route::get('/{generation}/view', [DocumentationDownloadController::class, 'view'])
        ->name('view');

    Route::get('/{generation}/preview', [DocumentationDownloadController::class, 'preview'])
        ->name('preview');
});
