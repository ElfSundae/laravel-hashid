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
        $config = $this->app['config']->get("hashid.connections.{$name}", []);

        if (isset($this->customCreators[$name])) {
            return $this->customCreators[$name]($config, $this->app, $name);
        }

        $driver = Arr::pull($config, 'driver');

        return $this->createConnectionForDriver($name, $driver, $config);
    }

    /**
     * Create a new hashid connection instance for the driver.
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

        // We will check to see if a creator method exists for the given driver,
        // and will call the Closure if so, which allows us to have a more generic
        // resolver for the drivers themselves which applies to all connections.
        if (isset($this->customCreators[$driver])) {
            return $this->customCreators[$driver]($config, $this->app, $name);
        }

        if ($this->app->bound($key = "hashid.driver.{$driver}")) {
            if ($this->app->isShared($key)) {
                return $this->app->make($key);
            }

            $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

            return $this->app->{$makeWith}($key, [
                'app' => $this->app,
                'config' => $config,
            ]);
        }

        throw new InvalidArgumentException("Unsupported driver [$driver]");
    }
}
