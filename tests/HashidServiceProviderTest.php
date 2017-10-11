<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Mockery as m;
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
        $this->assertSame($manager->connection(), $this->app['hashid.connection']);
    }

    public function testLumenInstantiation()
    {
        $app = m::mock('Laravel\Lumen\Application');
        $app->shouldReceive('configure')
            ->with('hashid')
            ->andThrow(new FooException);
        $this->expectException(FooException::class);
        $service = new HashidServiceProvider($app);
        $service->register();
    }
}

class FooException extends \Exception
{
}
