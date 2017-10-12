<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;
use Illuminate\Contracts\Container\Container;

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
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $manager->extend('foo', function ($config) {
            $this->assertEquals(['driver' => 'foo-driver', 'key' => 'value'], $config);

            return 'FooConnection';
        });
        $this->assertSame('FooConnection', $manager->connection('foo'));
    }

    public function testInvokedCustomDriverCreator()
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
            return new FooDriver($config);
        });

        $fooConnection = $manager->connection('foo');
        $this->assertInstanceOf(FooDriver::class, $fooConnection);
        $this->assertEquals(['key' => 'value'], $fooConnection->config);

        $fooDriver = $manager->connection('foo-driver');
        $this->assertInstanceOf(FooDriver::class, $fooDriver);
        $this->assertEquals([], $fooDriver->config);
    }

    public function testInvokedCustomCreatorWithDependencies()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $manager->extend('foo', function (Container $app, $config) {
            $this->assertSame($this->app, $app);
            $this->assertEquals(['driver' => 'foo-driver', 'key' => 'value'], $config);

            return 'FooConnection';
        });
        $this->assertSame('FooConnection', $manager->connection('foo'));

        $manager->extend('bar-driver', function (Container $app, $config) {
            $this->assertSame($this->app, $app);
            $this->assertEquals([], $config);

            return 'BarDriver';
        });
        $this->assertSame('BarDriver', $manager->connection('bar-driver'));
    }

    public function testResolvedDriverBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $this->app->bind('foo-driver', FooDriver::class);

        $fooConnection = $manager->connection('foo');
        $this->assertInstanceOf(FooDriver::class, $fooConnection);
        $this->assertEquals(['key' => 'value'], $fooConnection->config);

        $fooDriver = $manager->connection('foo-driver');
        $this->assertInstanceOf(FooDriver::class, $fooDriver);
        $this->assertEquals([], $fooDriver->config);
    }

    public function testResolvedDriverBindingUsingShortBindingName()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $this->app->bind('hashid.driver.foo-driver', FooDriver::class);

        $fooConnection = $manager->connection('foo');
        $this->assertInstanceOf(FooDriver::class, $fooConnection);
        $this->assertEquals(['key' => 'value'], $fooConnection->config);

        $fooDriver = $manager->connection('foo-driver');
        $this->assertInstanceOf(FooDriver::class, $fooDriver);
        $this->assertEquals([], $fooDriver->config);
    }

    public function testResolvedSharedDriverBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $driver = new FooDriver;
        $this->app->instance('foo-driver', $driver);

        $this->assertSame($driver, $manager->connection('foo'));
        $this->assertSame($driver, $manager->connection('foo-driver'));
    }

    public function testResolvedDriverBindingWithDependencies()
    {
        $manager = $this->getManager([
            'connections' => [
                'bar' => [
                    'driver' => 'bar-driver',
                    'key' => 'value',
                ],
            ],
        ]);

        $this->app->bind('bar-driver', BarDriver::class);

        $barConnection = $manager->connection('bar');
        $this->assertInstanceOf(BarDriver::class, $barConnection);
        $this->assertSame($this->app, $barConnection->container);
        $this->assertEquals(['key' => 'value'], $barConnection->config);

        $barDriver = $manager->connection('bar-driver');
        $this->assertInstanceOf(BarDriver::class, $barDriver);
        $this->assertSame($this->app, $barDriver->container);
        $this->assertEquals([], $barDriver->config);
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

class FooDriver
{
    public $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }
}

class BarDriver
{
    public $container;
    public $config;

    public function __construct(Container $container = null, $config = null)
    {
        $this->container = $container;
        $this->config = $config;
    }
}
