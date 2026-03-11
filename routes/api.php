<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ScreenshotController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('_healthz', HealthCheckController::class);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('screenshot', [ScreenshotController::class, 'generate'])->name('screenshot');
        Route::post('pdf', [PdfController::class, 'generate'])->name('pdf');
    });

    if (App::environment('local')) {
        Route::get('screenshot', [ScreenshotController::class, 'generate'])->name('screenshot.local');
        Route::get('pdf', [PdfController::class, 'generate'])->name('pdf.local');
    }
});
