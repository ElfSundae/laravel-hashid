<?php

namespace ElfSundae\Laravel\Hashid;

use ReflectionClass;
use Illuminate\Support\ServiceProvider;

class HashidServiceProvider extends ServiceProvider
{
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
        if (is_a($this->app, 'Laravel\Lumen\Application')) {
            $this->app->configure('hashid');
        }

        $this->mergeConfigFrom($config = __DIR__.'/../config/hashid.php', 'hashid');

        if ($this->app->runningInConsole()) {
            $this->publishes([$config => base_path('config/hashid.php')], 'hashid');
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

        foreach ($this->getAliasesBindings() as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }

        $this->app->bind('hashid.connection', function ($app) {
            return $app['hashid']->connection();
        });
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

        return is_null($reflector->getConstructor())
            ? new $class : $reflector->newInstanceArgs($args);
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
    protected function getAliasesBindings()
    {
        return [
            'hashid.driver.base62' => Base62Driver::class,
            'hashid.driver.base62.integer' => Base62IntegerDriver::class,
            'hashid.driver.hashids' => HashidsDriver::class,
            'hashid.driver.hashids.hex' => HashidsHexDriver::class,
            'hashid.driver.hashids.integer' => HashidsIntegerDriver::class,
            'hashid.driver.hashids.string' => HashidsStringDriver::class,
            'hashid.driver.optimus' => OptimusDriver::class,
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
                Console\OptimusGenerateCommand::class,
            ]);
        }
    }
}
