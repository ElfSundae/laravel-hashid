<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class CommandsTest extends TestCase
{
    public function testAlphabetGenerateCommand()
    {
        $this->assertSame(0, $this->getArtisan()->call('hashid:alphabet'));
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
