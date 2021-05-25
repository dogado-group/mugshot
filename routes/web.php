
<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/{path?}', [HomeController::class, 'index'])
    ->where('path', '.+')
    ->name('home');
