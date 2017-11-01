<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Support\Manager;

class HashidManager extends Manager
{
    /**
     * Get a hashid connection instance.
     *
     * @param  string|null  $name
     * @return mixed
     */
    public function connection($name = null)
    {
        return $this->driver($name);
    }

    /**
     * Get all of the created connections.
     *
     * @return array
     */
    public function getConnections()
    {
        return $this->getDrivers();
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['hashid.default'];
    }

    /**
     * Set the default connection name.
     *
     * @param  string  $name
     * @return $this
     */
    public function setDefaultConnection($name)
    {
        $this->app['config']['hashid.default'] = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return $this->getDefaultConnection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createDriver($name)
    {
        $config = Arr::get($this->app['config']['hashid.connections'], $name, []);

        return $this->createForConnection($name, $config) ?:
            $this->createForDriver(Arr::pull($config, 'driver', $name), $config);
    }

    /**
     * Create a new driver instance for the given connection.
     *
     * @param  string  $name
     * @param  array  $config
     * @return mixed
     */
    protected function createForConnection($name, array $config = [])
    {
        if (isset($this->customCreators[$name])) {
            return $this->callCustom($name, compact('config'));
        }
    }

    /**
     * Create a new driver instance for the given driver.
     *
     * We will check to see if a creator method exists for the given driver,
     * and will call the Closure if so, which allows us to have a more generic
     * resolver for the drivers themselves which applies to all connections.
     *
     * @param  string  $driver
     * @param  array  $config
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createForDriver($driver, array $config = [])
    {
        if (isset($this->customCreators[$driver])) {
            return $this->callCustom($driver, compact('config'));
        }

        if ($binding = $this->getBindingKeyForDriver($driver)) {
            return $this->resolveBinding($binding, compact('config'));
        }

        throw new InvalidArgumentException("Unsupported driver [$driver]");
    }

    /**
     * Call a custom creator.
     *
     * @param  string  $key
     * @param  array  $parameters
     * @return mixed
     */
    protected function callCustom($key, array $parameters = [])
    {
        return $this->app->call($this->customCreators[$key], $parameters);
    }

    /**
     * Get the binding key for the driver.
     *
     * @param  string  $driver
     * @return string|null
     */
    protected function getBindingKeyForDriver($driver)
    {
        if (class_exists($driver)) {
            return $driver;
        }

        if ($this->app->bound($key = "hashid.driver.$driver")) {
            return $key;
        }
    }

    /**
     * Resolve the given binding from the container.
     *
     * NOTE:
     * `Container::make($abstract, $parameters)` which can pass additional
     * parameters to the constructor was removed in Laravel 5.4
     * (https://github.com/laravel/internals/issues/391), but then re-added
     * as `makeWith()` in v5.4.16 (https://github.com/laravel/framework/pull/18271).
     * And in L55 the `makeWith()` is just an alias to `make()`.
     *
     * @param  string  $key
     * @param  array  $parameters
     * @return mixed
     */
    protected function resolveBinding($key, array $parameters = [])
    {
        if ($this->app->isShared($key)) {
            return $this->app->make($key);
        }

        $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

        return $this->app->$makeWith($key, $parameters);
    }
}
