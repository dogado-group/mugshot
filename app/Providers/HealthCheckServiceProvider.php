<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;

class HealthCheckServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Health::checks([
            DebugModeCheck::new(),
            UsedDiskSpaceCheck::new()
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
