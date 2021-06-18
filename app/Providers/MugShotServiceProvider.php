<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Browsershot\Browsershot;

class MugShotServiceProvider extends ServiceProvider
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

            $instance = (new Browsershot())
                ->userAgent($config['request']['useragent'])
                ->ignoreHttpsErrors();

            if (!is_null($config['puppeteer']['node'])) {
                $instance->setNodeBinary($config['puppeteer']['node']);
            }
            if (!is_null($config['puppeteer']['npm'])) {
                $instance->setNpmBinary($config['puppeteer']['npm']);
            }
            if (!is_null($config['puppeteer']['nodeModulesPath'])) {
                $instance->setNodeModulePath($config['puppeteer']['nodeModulesPath']);
            }
            if (!is_null($config['puppeteer']['chrome'])) {
                $instance->setChromePath($config['puppeteer']['chrome']);
            }
            if (!is_null($config['puppeteer']['proxyServer'])) {
                $instance->setProxyServer($config['puppeteer']['proxyServer']);
            }

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
