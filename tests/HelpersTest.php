<?php

namespace ElfSundae\Laravel\Hashid\Test;

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

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
