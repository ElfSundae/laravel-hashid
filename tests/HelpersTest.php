<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Mockery as m;
use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class HelpersTest extends TestCase
{
    public function test_hashid()
    {
        $this->app['config']->set('hashid', [
            'default' => 'foo',
        ]);
        $this->app['hashid']->extend('foo', function () {
            return 'FooConnection';
        });
        $this->app['hashid']->extend('bar', function () {
            return 'BarConnection';
        });
        $this->assertSame('FooConnection', hashid());
        $this->assertSame('FooConnection', hashid('foo'));
        $this->assertSame('BarConnection', hashid('bar'));
    }

    public function test_hashid_encode()
    {
        $connection = m::mock('connection');
        $connection->shouldReceive('encode')
            ->with('text')
            ->once()
            ->andReturn('OK');
        $this->app['hashid']->extend('foo', function () use ($connection) {
            return $connection;
        });
        $this->assertSame('OK', hashid_encode('text', 'foo'));
    }

    public function test_hashid_decode()
    {
        $connection = m::mock('connection');
        $connection->shouldReceive('decode')
            ->with('text')
            ->once()
            ->andReturn('OK');
        $this->app['hashid']->extend('foo', function () use ($connection) {
            return $connection;
        });
        $this->assertSame('OK', hashid_decode('text', 'foo'));
    }

    public function test_config_path()
    {
        $this->assertSame(
            $this->app->basePath().'/config/foo/bar',
            config_path('foo/bar')
        );
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
