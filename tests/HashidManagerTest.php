<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HashidManager;
use Illuminate\Contracts\Container\Container;
use Orchestra\Testbench\TestCase;

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

    public function testUnsupportedDriver()
    {
        $manager = $this->getManager();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported driver [foo]');
        $manager->connection('foo');
    }

    public function testInvokedCustomConnectionCreator()
    {
        $manager = $this->getManager([
            'connections' => [
                'test' => [
                    'driver' => 'test-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $manager->extend('test', function ($config) {
            $this->assertEquals(['driver' => 'test-driver', 'key' => 'value'], $config);

            return 'TestConnection';
        });
        $this->assertSame('TestConnection', $manager->connection('test'));
    }

    public function testInvokedCustomDriverCreator()
    {
        $manager = $this->getManager([
            'connections' => [
                'test' => [
                    'driver' => 'test-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $manager->extend('test-driver', function ($config) {
            return new TestDriver(null, $config);
        });

        $connection = $manager->connection('test');
        $this->assertInstanceOf(TestDriver::class, $connection);
        $this->assertEquals(['key' => 'value'], $connection->config);

        $driver = $manager->connection('test-driver');
        $this->assertInstanceOf(TestDriver::class, $driver);
        $this->assertEquals([], $driver->config);
    }

    public function testInvokedCustomCreatorWithDependencies()
    {
        $manager = $this->getManager([
            'connections' => [
                'test' => [
                    'driver' => 'test-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $manager->extend('test', function (Container $app, $config) {
            $this->assertSame($this->app, $app);
            $this->assertEquals(['driver' => 'test-driver', 'key' => 'value'], $config);

            return 'TestConnection';
        });
        $this->assertSame('TestConnection', $manager->connection('test'));

        $manager->extend('foo-driver', function (Container $app, $config) {
            $this->assertSame($this->app, $app);
            $this->assertEquals([], $config);

            return 'FooDriver';
        });
        $this->assertSame('FooDriver', $manager->connection('foo-driver'));
    }

    public function testResolvedDriverBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'test' => [
                    'driver' => 'test-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $this->app->bind('hashid.driver.test-driver', TestDriver::class);

        $connection = $manager->connection('test');
        $this->assertInstanceOf(TestDriver::class, $connection);
        $this->assertSame($this->app, $connection->container);
        $this->assertEquals(['key' => 'value'], $connection->config);

        $driver = $manager->connection('test-driver');
        $this->assertInstanceOf(TestDriver::class, $driver);
        $this->assertSame($this->app, $driver->container);
        $this->assertEquals([], $driver->config);
    }

    public function testResolvedDriverAsClass()
    {
        $manager = $this->getManager([
            'connections' => [
                'test' => [
                    'driver' => TestDriver::class,
                    'key' => 'value',
                ],
            ],
        ]);

        $connection = $manager->connection('test');
        $this->assertInstanceOf(TestDriver::class, $connection);
        $this->assertSame($this->app, $connection->container);
        $this->assertEquals(['key' => 'value'], $connection->config);

        $driver = $manager->connection(TestDriver::class);
        $this->assertInstanceOf(TestDriver::class, $driver);
        $this->assertSame($this->app, $driver->container);
        $this->assertEquals([], $driver->config);
    }

    public function testResolvedSharedDriverBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'test' => [
                    'driver' => 'test-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $driver = new TestDriver;
        $this->app->instance('hashid.driver.test-driver', $driver);

        $this->assertSame($driver, $manager->connection('test'));
        $this->assertSame($driver, $manager->connection('test-driver'));
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
    public $container;
    public $config;

    public function __construct(?Container $container = null, $config = null)
    {
        $this->container = $container;
        $this->config = $config;
    }
}
