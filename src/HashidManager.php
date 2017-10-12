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
        $config = $this->configuration($name);

        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($name, compact('config'));
        }

        $driver = Arr::pull($config, 'driver', $name);

        return $this->createConnectionForDriver($driver, $config);
    }

    /**
     * {@inheritdoc}
     */
    protected function callCustomCreator($driver)
    {
        return $this->app->call(
            $this->customCreators[$driver],
            func_num_args() > 1 ? func_get_arg(1) : []
        );
    }

    /**
     * Create a new hashid connection instance for the driver.
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
    protected function createConnectionForDriver($driver, array $config = [])
    {
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver, compact('config'));
        }

        if ($binding = $this->getBindingForDriver($driver)) {
            return $this->resolveFromContainer($binding, compact('config'));
        }

        throw new InvalidArgumentException("Unsupported driver [$driver]");
    }

    /**
     * Get the container binding for the driver.
     *
     * @param  string  $driver
     * @return string|null
     */
    protected function getBindingForDriver($driver)
    {
        return Arr::first(
            [$driver, "hashid.driver.".$driver],
            [$this->app, 'bound']
        );
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
     * @param  string  $abstract
     * @param  array  $parameters
     * @return mixed
     */
    protected function resolveFromContainer($abstract, array $parameters = [])
    {
        if (empty($parameters) && $this->app->isShared($abstract)) {
            return $this->app->make($abstract);
        }

        $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

        return $this->app->{$makeWith}($abstract, $parameters);
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     */
    protected function configuration($name)
    {
        return Arr::get($this->app['config']['hashid.connections'], $name, []);
    }
}
