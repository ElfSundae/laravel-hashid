<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;

abstract class DriverTestCase extends TestCase
{
    /**
     * The driver class or binding name.
     *
     * @var string
     */
    protected $driver;

    protected function makeDriver($driver = null, array $config = [])
    {
        if (is_array($driver)) {
            $tmp = $driver;
            $driver = $config;
            $config = $tmp;
        }

        if (is_object($driver)) {
            return $driver;
        }

        $makeWith = method_exists($this->app, 'makeWith') ? 'makeWith' : 'make';

        return $this->app->$makeWith($driver ?: $this->driver, compact('config'));
    }

    protected function assertEncodedData($data, $encodedData, $driver = null, array $config = [])
    {
        $driver = $this->makeDriver($driver, $config);
        $encoded = $driver->encode($data);
        $this->assertSame($encodedData, $encoded);
        $decoded = $driver->decode($encodedData);
        $this->assertSame($data, $decoded);
    }

    protected function assertReversible($data, $driver = null, array $config = [])
    {
        $driver = $this->makeDriver($driver, $config);
        $encoded = $driver->encode($data);
        $decoded = $driver->decode($encoded);
        $this->assertSame($data, $decoded);
    }

    protected function assertUniformEncoding($data, $driver = null, array $config = [])
    {
        $driver = $this->makeDriver($driver, $config);
        $encoded1 = $driver->encode($data);
        $encoded2 = $driver->encode($data);
        $this->assertSame($encoded1, $encoded2);
    }

    protected function runForBytes($driver = null, array $config = [])
    {
        $this->runForRandomBytes($driver, $config);
        $this->runForLeadingZeroBytes($driver, $config);
        $this->runForLeadingNullTerminatorBytes($driver, $config);
    }

    protected function runForRandomBytes($driver = null, array $config = [])
    {
        $data = random_bytes(random_int(64, 256));
        $this->assertReversible($data, $driver, $config);
    }

    protected function runForLeadingZeroBytes($driver = null, array $config = [])
    {
        $data = hex2bin('03486eea2de87439');
        $this->assertReversible($data, $driver, $config);
    }

    protected function runForLeadingNullTerminatorBytes($driver = null, array $config = [])
    {
        $data = hex2bin('00616263313233');
        $this->assertReversible($data, $driver, $config);
    }

    protected function runForIntegers($driver = null, array $config = [])
    {
        $this->runForIntegersWith(0, PHP_INT_MAX, $driver, $config);
    }

    protected function runForIntegersWith($min = 0, $max = PHP_INT_MAX, $driver = null, array $config = [])
    {
        $this->assertReversible(random_int($min, $max), $driver, $config);
        $this->assertReversible($min, $driver, $config);
        $this->assertReversible($max, $driver, $config);
    }
}
