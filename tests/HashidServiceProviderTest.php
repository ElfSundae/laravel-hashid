<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base64Driver;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;
use Orchestra\Testbench\TestCase;

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

        $this->assertSame($this->app['hashid.driver.base64'], $this->app[Base64Driver::class]);
    }
}
