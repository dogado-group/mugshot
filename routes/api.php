<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], static function (): void {
    Route::get('/status', [StatusController::class, 'status'])
        ->name('status');

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
