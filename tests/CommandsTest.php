<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Artisan;
use ElfSundae\Laravel\Hashid\HashidServiceProvider;

class CommandsTest extends TestCase
{
    public function testAlphabetGenerateCommand()
    {
        $this->assertSame(0, Artisan::call('hashid:alphabet'));
    }

    protected function getPackageProviders($app)
    {
        return [HashidServiceProvider::class];
    }
}
