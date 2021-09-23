<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Browsershot\Browsershot;

class MugShotServiceProvider extends ServiceProvider
{
    protected string $configFile = '/config/mugshot.php';

    public function register()
    {
        $this->app->singleton(Browsershot::class, function ($app) {
            $config = $app->make('config')['mugshot'];

            $instance = (new Browsershot())
                ->timeout($config['timeout'])
                ->userAgent($config['request']['useragent'])
                ->ignoreHttpsErrors();

            if (!$config['puppeteer']['sandbox']) {
                $instance->noSandbox();
            }
            if (!empty($config['puppeteer']['node'])) {
                $instance->setNodeBinary($config['puppeteer']['node']);
            }
            if (!empty($config['puppeteer']['npm'])) {
                $instance->setNpmBinary($config['puppeteer']['npm']);
            }
            if (!empty($config['puppeteer']['nodeModulesPath'])) {
                $instance->setNodeModulePath($config['puppeteer']['nodeModulesPath']);
            }
            if (!empty($config['puppeteer']['chrome'])) {
                $instance->setChromePath($config['puppeteer']['chrome']);
            }
            if (!empty($config['puppeteer']['proxyServer'])) {
                $instance->setProxyServer($config['puppeteer']['proxyServer']);
            }

            if (
                !empty($config['puppeteer']['remoteChromeInstance']['host'])
                && !empty($config['puppeteer']['remoteChromeInstance']['port'])
            ) {
                $instance->setRemoteInstance(
                    $config['puppeteer']['remoteChromeInstance']['host'],
                    $config['puppeteer']['remoteChromeInstance']['port'],
                );
            }

            return $instance;
        });
    }

    public function boot()
    {
        $this->publishes([
            $this->configFile => config_path('mugshot.php')
        ], 'config');
    }

    public function provides()
    {
        return [Browsershot::class];
    }
}
