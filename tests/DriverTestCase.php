<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;

abstract class DriverTestCase extends TestCase
{
    protected $driver = '';

    protected function makeDriver($config = [], $driver = null)
    {
        $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

        return $this->app->{$makeWith}($driver ?: $this->driver, [
            'app' => $this->app,
            'config' => $config,
        ]);
    }

    protected function callEncodingString($driver = null, $times = 1)
    {
        $driver = $driver ?: $this->makeDriver();

        for ($i=0; $i < $times; $i++) {
            $data = random_bytes(random_int(0, 128));
            $decoded = $driver->decode($driver->encode($data));
            $this->assertSame($data, $decoded);
        }
    }

    protected function callEncodingInteger($driver = null, $times = 1)
    {
        $driver = $driver ?: $this->makeDriver();

        for ($i=0; $i < $times; $i++) {
            $data = random_int(0, PHP_INT_MAX);
            $decoded = $driver->decode($driver->encode($data));
            $this->assertSame($data, $decoded);
        }
    }

    protected function callEncodingMaxInteger($driver = null)
    {
        $driver = $driver ?: $this->makeDriver();

        $data = PHP_INT_MAX;
        $decoded = $driver->decode($driver->encode($data));
        $this->assertSame($data, $decoded);
    }
}
