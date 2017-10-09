<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;

class HashidManagerTest extends TestCase
{
    public function testAccessDefaultConnection()
    {
        $manager = $this->getManager([
            'default' => 'foo',
        ]);
        $this->assertSame('foo', $manager->getDefaultConnection());
        $this->assertSame($manager, $manager->setDefaultConnection('bar'));
        $this->assertSame('bar', $manager->getDefaultConnection());
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
        $manager->extend('foo', function ($config, $app, $name) {
            $this->assertEquals(['key' => 'value'], $config);
            $this->assertSame($this->app, $app);
            $this->assertSame('foo', $name);

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
        $manager->extend('foo-driver', function ($config, $app, $name) {
            $this->assertEquals(['key' => 'value'], $config);
            $this->assertSame($this->app, $app);
            $this->assertSame('foo', $name);

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
        $this->assertSame($this->app, $connection->app);
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
        $this->app->singleton('hashid.driver.foo-driver', function ($app) {
            return new TestDriver($app, ['bar']);
        });
        $connection = $manager->connection('foo');
        $this->assertSame($connection, $this->app['hashid.driver.foo-driver']);
        $this->assertInstanceOf(TestDriver::class, $connection);
        $this->assertEquals(['bar'], $connection->config);
        $this->assertSame($this->app, $connection->app);
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
    public $app;
    public $config;

    public function __construct($app = null, $config = null)
    {
        $this->app = $app;
        $this->config = $config;
    }
}
