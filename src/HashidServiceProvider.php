<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

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
        if ($this->app instanceof LumenApplication) {
            $this->app->configure('hashid'); // @codeCoverageIgnore
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
        $this->app->singleton('hashid', function ($app) {
            return new HashidManager($app);
        });
        $this->app->alias('hashid', HashidManager::class);

        foreach ($this->getSingletonDrivers() as $class) {
            $this->app->singleton(
                $key = $this->getBindingKeyForDriver($class),
                function () use ($class) {
                    return new $class;
                }
            );
            $this->app->alias($key, $class);
        }

        foreach ($this->getNonSingletonDrivers() as $class) {
            $this->app->bind($this->getBindingKeyForDriver($class), $class);
        }
    }

    /**
     * Get singleton drivers classes.
     *
     * @return array
     */
    protected function getSingletonDrivers()
    {
        return [
            Base64Driver::class,
            Base64IntegerDriver::class,
            HexDriver::class,
            HexIntegerDriver::class,
        ];
    }

    /**
     * Get non-singleton drivers classes.
     *
     * @return array
     */
    protected function getNonSingletonDrivers()
    {
        return [
            Base62Driver::class,
            Base62IntegerDriver::class,
            HashidsDriver::class,
            HashidsHexDriver::class,
            HashidsIntegerDriver::class,
            HashidsStringDriver::class,
            OptimusDriver::class,
        ];
    }

    /**
     * Get the binding key for the driver class.
     *
     * @param  string  $class
     * @return string
     */
    protected function getBindingKeyForDriver($class)
    {
        return 'hashid.driver.'.Str::snake(
            preg_replace('#Driver$#', '', class_basename($class))
        );
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
