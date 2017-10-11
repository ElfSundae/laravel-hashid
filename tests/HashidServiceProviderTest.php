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

        $this->app->register($service = HashidServiceProvider::class);
        $this->app->isDeferredService($service);

        $this->assertSame($this->app['hashid'], $this->app[HashidManager::class]);
        $this->assertSame($this->app['hashid'], Hashid::getFacadeRoot());
    }

    public function testLumenInstantiation()
    {
        $app = m::mock('Laravel\Lumen\Application');
        $app->shouldReceive('configure')
            ->with('hashid')
            ->andThrow(new \Exception('exception message', 100));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('exception message');
        $this->expectExceptionCode(100);
        $service = new HashidServiceProvider($app);
        $service->register();
    }
}
