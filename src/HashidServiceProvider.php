<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\ServiceProvider;

class HashidServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hashid.php', 'hashid');

        $this->registerServices();

        if ($this->app->runningInConsole()) {
            $this->registerForConsole();
        }
    }

    /**
     * Register bindings.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton('hashid', function ($app) {
            return new HashidManager($app);
        });
        $this->app->alias('hashid', HashidManager::class);

        $this->app->bind('hashid.connection.base62', function ($app, $parameters) {
            return new Base62Connection(...$parameters);
        });

        $this->app->singleton('hashid.connection.base64', function ($app, $parameters) {
            return new Base64Connection(...$parameters);
        });
    }

    /**
     * Register for the console application.
     *
     * @return void
     */
    protected function registerForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/hashid.php' => config_path('hashid.php'),
        ], 'hashid');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'hashid', HashidManager::class,
            'hashid.connection.base62',
            'hashid.connection.base64',
        ];
    }
}
