<?php

namespace RachidLaasri\LaravelInstaller\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use RachidLaasri\LaravelInstaller\Commands\FreshInstall;
use RachidLaasri\LaravelInstaller\Middleware\CanInstall;
use RachidLaasri\LaravelInstaller\Middleware\CanUpdate;
use RachidLaasri\LaravelInstaller\Middleware\CanVerify;
use RachidLaasri\LaravelInstaller\Middleware\SecurityCheck;

class LaravelInstallerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    protected $env = '.env';
    protected $envExample = '.env.example';


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->publishFiles();
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Bootstrap the application events.
     *
     * @param $void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('install',[CanInstall::class]);
        $router->middlewareGroup('update',[CanUpdate::class]);
        $router->middlewareGroup('verify',[CanVerify::class]);
        $router->middlewareGroup('check',[SecurityCheck::class]);

        if ($this->app->runningInConsole()) {
            $this->commands([FreshInstall::class]);
        }
    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->publishes([
            __DIR__.'/../Config/installer.php' => base_path('config/installer.php'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../assets' => public_path('installer'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../Views' => base_path('resources/views/vendor/installer'),
        ], 'laravelinstaller');

    }
}
