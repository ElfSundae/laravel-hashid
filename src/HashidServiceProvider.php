<?php

namespace ElfSundae\Laravel\Hashid;

use ReflectionClass;
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
     * Register service bindings.
     *
     * @return void
     */
    protected function registerServices()
    {
        foreach ($this->getSingletonBindings() as $abstract => $concrete) {
            $this->app->singleton($abstract, function ($app) use ($concrete) {
                $reflector = new ReflectionClass($concrete);

                if (is_null($reflector->getConstructor())) {
                    return new $concrete;
                }

                return $reflector->newInstanceArgs([$app]);
            });

            $this->app->alias($abstract, $concrete);
        }

        foreach ($this->getClassAliases() as $abstract => $alias) {
            $this->app->alias($abstract, $alias);
        }
    }

    /**
     * Get singleton bindings to be registered.
     *
     * @return array
     */
    protected function getSingletonBindings()
    {
        return [
            'hashid' => HashidManager::class,
            'hashid.connection.hex' => HexConnection::class,
        ];
    }

    /**
     * Get class aliases to be registered.
     *
     * @return array
     */
    protected function getClassAliases()
    {
        return [
            Base62Connection::class => 'hashid.connection.base62',
            Base64Connection::class => 'hashid.connection.base64',
        ];
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
        ];
    }
}
