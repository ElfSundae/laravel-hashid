<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\HexConnection;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class HashidServiceProviderTest extends TestCase
{
    public function testBindings()
    {
        $this->assertSame($this->app['hashid'], $this->app[HashidManager::class]);
        $this->assertSame($this->app['hashid.connection.hex'], $this->app[HexConnection::class]);
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
