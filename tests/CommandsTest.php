<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use Illuminate\Contracts\Console\Kernel;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class CommandsTest extends TestCase
{
    public function testAlphabetGenerateCommand()
    {
        $this->assertSame(0, $this->artisan('hashid:alphabet'));
    }

    public function testOptimusGenerateCommand()
    {
        $this->assertSame(0, $this->artisan('hashid:optimus'));
    }

    public function artisan($command, $parameters = [])
    {
        return $this->app[Kernel::class]->call($command, $parameters);
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
