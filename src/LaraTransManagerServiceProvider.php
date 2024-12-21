<?php

namespace Soufian212\LaraTransManager;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Soufian212\LaraTransManager\Services\TranslationService;
use Soufian212\LaraTransManager\Http\Middleware\HandleInertiaRequests;

class LaraTransManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'laratransmanager-migrations');
        $this->app->singleton('laratransmanager.translation', function ($app) {
            return new TranslationService();
        });
        $this->publishes([
                    __DIR__ . '/config/config.php' => config_path('translation.php'),
                ], 'laratransmanager-config');

        
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laratransmanager');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\InitCommand::class,
            ]);
        }

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', HandleInertiaRequests::class);
        Paginator::defaultView('vendor.pagination.custom');
        $this->publishes([
            __DIR__ . '/../resources/dist/vendor' => public_path('vendor/laratransmanager'),
        ], 'public');

    }
}
