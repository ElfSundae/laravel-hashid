<?php

namespace ElfSundae\Laravel\Hashid;

use ReflectionClass;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

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
        $this->setupAssets();

        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * Setup package assets.
     *
     * @return void
     */
    protected function setupAssets()
    {
        if ($this->app instanceof LumenApplication) {
            $this->app->configure('hashid');
        }

        $this->mergeConfigFrom($config = __DIR__.'/../config/hashid.php', 'hashid');

        if ($this->app->runningInConsole()) {
            $this->publishes([$config => config_path('hashid.php')], 'hashid');
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
                return $this->createInstance($concrete, [$app]);
            });

            $this->app->alias($abstract, $concrete);
        }

        foreach ($this->getClassAliases() as $abstract => $alias) {
            $this->app->alias($abstract, $alias);
        }
    }

    /**
     * Create a new instance from class name.
     *
     * @param  string  $class
     * @param  array  $args
     * @return mixed
     */
    protected function createInstance($class, array $args = [])
    {
        $reflector = new ReflectionClass($class);

        if (is_null($reflector->getConstructor())) {
            return new $class;
        }

        return $reflector->newInstanceArgs($args);
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
            'hashid.driver.base64' => Base64Driver::class,
            'hashid.driver.base64.integer' => Base64IntegerDriver::class,
            'hashid.driver.hex' => HexDriver::class,
            'hashid.driver.hex.integer' => HexIntegerDriver::class,
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
            Base62Driver::class => 'hashid.driver.base62',
            Base62IntegerDriver::class => 'hashid.driver.base62.integer',
        ];
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\AlphabetGenerateCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return array_merge(
            array_keys($bindings = $this->getSingletonBindings()),
            array_values($bindings)
        );
    }
}
