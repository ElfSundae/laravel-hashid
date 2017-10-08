<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;

abstract class ConnectionTestCase extends TestCase
{
    protected $connection = '';

    protected function getConnection($config = [], $connection = null)
    {
        $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

        return $this->app->{$makeWith}($connection ?: $this->connection, [
            'app' => $this->app,
            'config' => $config,
        ]);
    }
}
