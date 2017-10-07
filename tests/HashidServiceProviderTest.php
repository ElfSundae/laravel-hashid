<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\Base62Connection;
use ElfSundae\Laravel\Hashid\Base64Connection;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class HashidServiceProviderTest extends TestCase
{
    public function testBindings()
    {
        $this->assertSame($this->app['hashid'], $this->app[HashidManager::class]);
        $this->assertInstanceOf(
            Base62Connection::class,
            $this->app->make('hashid.connection.base62', [$this->app])
        );
        $this->assertInstanceOf(
            Base64Connection::class,
            $this->app->make('hashid.connection.base64')
        );
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
