<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\HexDriver;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class HashidServiceProviderTest extends TestCase
{
    public function testBindings()
    {
        $this->assertSame($this->app['hashid'], $this->app[HashidManager::class]);
        $this->assertSame($this->app['hashid.driver.hex'], $this->app[HexDriver::class]);
    }

    public function testFacades()
    {
        $this->assertSame($this->app['hashid'], Hashid::getFacadeRoot());
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
