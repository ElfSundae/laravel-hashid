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

    protected function assertReversible($data, $driver = null)
    {
        $driver = $driver ?: $this->makeDriver();
        $encoded = $driver->encode($data);
        $decoded = $driver->decode($encoded);
        $this->assertSame($data, $decoded);
    }

    protected function assertUniformEncoding($data, $driver = null)
    {
        $driver = $driver ?: $this->makeDriver();
        $encoded1 = $driver->encode($data);
        $encoded2 = $driver->encode($data);
        $this->assertSame($encoded1, $encoded2);
    }

    protected function runForBytes($driver = null)
    {
        $this->runForRandomBytes($driver);
        $this->runForLeadingZeroBytes($driver);
        // $this->runForLeadingNullTerminatorBytes($driver);
    }

    protected function runForRandomBytes($driver = null)
    {
        $data = random_bytes(random_int(1, 128));
        $this->assertReversible($data, $driver);
    }

    protected function runForLeadingZeroBytes($driver = null)
    {
        $data = hex2bin('03486eea2de87439');
        $this->assertReversible($data, $driver);
    }

    protected function runForLeadingNullTerminatorBytes($driver = null)
    {
        $data = hex2bin('00616263313233');
        $this->assertReversible($data, $driver);
    }

    protected function runForIntegers($driver = null)
    {
        $this->assertReversible(random_int(0, PHP_INT_MAX), $driver);
        $this->assertReversible(0, $driver);
        $this->assertReversible(PHP_INT_MAX, $driver);
    }
}
