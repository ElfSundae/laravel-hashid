<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class HashidServiceProviderTest extends TestCase
{
    public function testInstantiation()
    {
        $this->assertSame($this->app['hashid'], $this->app[HashidManager::class]);
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
