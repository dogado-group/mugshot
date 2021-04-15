<?php

use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], static function (): void {
    Route::get('/status', [StatusController::class, 'status'])
        ->name('status');

    if (App::environment('local')) {
        Route::get('/screenshot', [ScreenshotController::class, 'capture'])
            ->name('screenshot');
    }

    Route::post('/screenshot', [ScreenshotController::class, 'capture'])
        ->name('screenshot')
        ->middleware('auth:sanctum');
});
