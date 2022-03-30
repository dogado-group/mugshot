<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ScreenshotController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], static function (): void {
    Route::get('_healthz', HealthCheckController::class);

    if (App::environment('local')) {
        Route::get('/screenshot', [ScreenshotController::class, 'generate'])
            ->name('screenshot');
    }

    Route::post('/screenshot', [ScreenshotController::class, 'generate'])
        ->name('screenshot')
        ->middleware('auth:sanctum');

    if (App::environment('local')) {
        Route::get('/pdf', [PdfController::class, 'generate'])
            ->name('pdf');
    }

    Route::post('/pdf', [PdfController::class, 'generate'])
        ->name('pdf')
        ->middleware('auth:sanctum');
});
