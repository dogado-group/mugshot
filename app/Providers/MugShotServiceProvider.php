<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;

class MugShotServiceProvider  extends ServiceProvider
{
    protected string $configFile = '/config/mugshot.php';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Browsershot::class, function ($app) {
            $config = $app->make('config')['mugshot'];

            $instance = new Browsershot();
            $instance
                ->setNodeBinary($config['puppeteer']['node'])
                ->setNpmBinary($config['puppeteer']['npm'])
                ->setProxyServer($config['puppeteer']['proxyServer'])
                ->setChromePath($config['puppeteer']['chrome'])
                ->userAgent($config['request']['useragent'])
                ->ignoreHttpsErrors()
            ;

            return $instance;
        });

    }

    /**
     * Bootstrap's Package Services
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configFile => config_path('mugshot.php')
        ], 'config');
    }

    /**
     * Services provided by this provider
     *
     * @return array
     */
    public function provides()
    {
        return [Browsershot::class];
    }
}
