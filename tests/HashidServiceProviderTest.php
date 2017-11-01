<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class HashidServiceProviderTest extends TestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(HashidServiceProvider::class, new HashidServiceProvider($this->app));
    }

    public function testBindings()
    {
        $this->app->register(HashidServiceProvider::class);

        $manager = $this->app['hashid'];
        $this->assertSame($manager, $this->app[HashidManager::class]);
        $this->assertSame($manager, Hashid::getFacadeRoot());
    }
}
