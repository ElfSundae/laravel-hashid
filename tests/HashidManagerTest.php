<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\Connection;
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
                'foo' => [
                    'key' => 'value',
                ],
            ],
        ]);
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
        $manager->extend('foo', function ($app, $config, $name) {
            $this->assertSame($this->app, $app);
            $this->assertEquals(['key' => 'value'], $config);
            $this->assertSame('foo', $name);

            return 'FooConnection';
        });
        $this->assertEquals('FooConnection', $manager->connection('foo'));
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
        $manager->extend('foo-driver', function ($app, $config, $name) {
            $this->assertSame($this->app, $app);
            $this->assertEquals(['key' => 'value'], $config);
            $this->assertSame('foo', $name);

            return 'FooConnection';
        });
        $this->assertEquals('FooConnection', $manager->connection('foo'));
    }

    public function testCreateConnectionWithBinding()
    {
        $manager = $this->getManager([
            'connections' => [
                'foo' => [
                    'driver' => 'foo-driver',
                    'key' => 'value',
                ],
            ],
        ]);
        $this->app->bind('hashid.connection.foo-driver', function ($app, $parameters) {
            return new TestConnection(...$parameters);
        });
        $connection = $manager->connection('foo');
        $this->assertInstanceOf(TestConnection::class, $connection);
        $this->assertSame($this->app, $connection->getApplication());
        $this->assertEquals(['key' => 'value'], $connection->getConfig());
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
        $this->assertEquals(['foo' => 'FooConnection', 'bar' => 'BarConnection'], $manager->getConnections());
    }

    protected function getManager($config = [])
    {
        $this->app['config']['hashid'] = $config;

        return new HashidManager($this->app);
    }
}

class TestConnection extends Connection
{
    public function getApplication()
    {
        return $this->app;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function encode($data)
    {
        return $data;
    }

    public function decode($data)
    {
        return $data;
    }
}
