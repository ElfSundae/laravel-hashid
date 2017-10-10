<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base62Driver;
use ElfSundae\Laravel\Hashid\Base62IntegerDriver;

class Base62DriverTest extends DriverTestCase
{
    protected $driver = Base62Driver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base62Driver::class, $this->makeDriver());
    }

    public function testEncoding()
    {
        $this->runForBytes();
        $this->runForIntegers(Base62IntegerDriver::class);
    }

    public function testEncodingWithCustomCharacters()
    {
        $this->runForBytes([
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ]);
        $this->runForIntegers(Base62IntegerDriver::class, [
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ]);
    }
}
