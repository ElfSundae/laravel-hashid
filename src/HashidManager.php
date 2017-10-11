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
            return $this->customCreators[$name]($config, $this->app, $name);
        }

        $driver = Arr::pull($config, 'driver');

        return $this->createConnectionForDriver($name, $driver, $config);
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

    /**
     * Create a new hashid connection instance for the driver.
     *
     * We will check to see if a creator method exists for the given driver,
     * and will call the Closure if so, which allows us to have a more generic
     * resolver for the drivers themselves which applies to all connections.
     *
     * @param  string  $name
     * @param  string  $driver
     * @param  array  $config
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createConnectionForDriver($name, $driver, array $config = [])
    {
        if (is_null($driver)) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        if (isset($this->customCreators[$driver])) {
            return $this->customCreators[$driver]($config, $this->app, $name);
        }

        if ($this->app->bound($key = "hashid.driver.{$driver}")) {
            return $this->resolveFromContainer($key, [
                'app' => $this->app,
                'config' => $config,
            ]);
        }

        throw new InvalidArgumentException("Unsupported driver [$driver]");
    }

    /**
     * Resolve the given type from the container.
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
        if ($this->app->isShared($abstract)) {
            return $this->app->make($abstract);
        }

        $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

        return $this->app->{$makeWith}($abstract, $parameters);
    }
}
