<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class CommandsTest extends TestCase
{
    public function testAlphabetGenerateCommand()
    {
        $this->assertSame(0, $this->callCommand('hashid:alphabet'));
    }

    public function testOptimusGenerateCommand()
    {
        $this->assertSame(0, $this->callCommand('hashid:optimus'));
    }

    protected function callCommand($command)
    {
        return $this->getArtisan()->call($command);
    }

    protected function getArtisan()
    {
        return $this->app['Illuminate\Contracts\Console\Kernel'];
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
