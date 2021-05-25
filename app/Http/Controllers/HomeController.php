<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        if (App::environment('local')) {
            abort(404);
        }

        return Redirect::away(config('mugshot.redirect'));
    }
}
