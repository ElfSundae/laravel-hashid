<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;

class HashidManagerTest extends TestCase
{
    public function testGetDefaultConnection()
    {
        $manager = $this->getManager([
            'default' => 'foo',
        ]);
        $this->assertSame('foo', $manager->getDefaultConnection());
    }

    public function testSetDefaultConnection()
    {
        $manager = $this->getManager();
        $this->assertSame($manager, $manager->setDefaultConnection('foo'));
        $this->assertSame('foo', $manager->getDefaultConnection());
        $this->assertSame('foo', $this->app['config']['hashid.default']);
    }

    public function testNoneDriver()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [],
            ],
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');
        $manager->connection('foo');
    }

    public function testUnsupportedDriver()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                ],
            ],
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported driver [foo-driver]');
        $manager->connection('foo');
    }

    public function testCreateConnectionWithCustomClosure()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'key' => 'value',
                ],
            ],
        ]);
        $manager->extend('foo', function ($config) {
            $this->assertEquals(['key' => 'value'], $config);

            return 'FooConnection';
        });
        $this->assertSame('FooConnection', $manager->connection('foo'));
    }

    public function testCreateConnectionWithCustomDriverClosure()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);
        $manager->extend('foo-driver', function ($config) {
            $this->assertEquals(['key' => 'value'], $config);

            return 'FooConnection';
        });
        $this->assertSame('FooConnection', $manager->connection('foo'));
    }

    public function testCreateConnectionWithDriverBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);
        $this->app->alias(TestDriver::class, 'hashid.driver.foo-driver');
        $connection = $manager->connection('foo');
        $this->assertInstanceOf(TestDriver::class, $connection);
        $this->assertEquals(['key' => 'value'], $connection->config);
    }

    public function testCreateConnectionWithSharedDriverBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);
        $driver = new TestDriver;
        $this->app->instance('hashid.driver.foo-driver', $driver);
        $connection = $manager->connection('foo');
        $this->assertSame($driver, $connection);
    }

    public function testGetConnections()
    {
        $manager = $this->getManager();
        $manager->extend('foo', function () {
            return 'FooConnection';
        });
        $manager->extend('bar', function () {
            return 'BarConnection';
        });
        $manager->connection('foo');
        $manager->connection('bar');
        $this->assertEquals([
            'foo' => 'FooConnection',
            'bar' => 'BarConnection',
        ], $manager->getConnections());
    }

    protected function getManager($config = [])
    {
        $this->app['config']['hashid'] = $config;

        return new HashidManager($this->app);
    }
}

class TestDriver
{
    public $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }
}
