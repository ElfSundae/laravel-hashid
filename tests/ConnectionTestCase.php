<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;

abstract class ConnectionTestCase extends TestCase
{
    protected $connection = '';

    protected function getConnection($config = [], $connection = null)
    {
        return $this->app->make($connection ?: $this->connection, [
            'app' => $this->app,
            'config' => $config,
        ]);
    }
}
