<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base62Driver;

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
        $this->runForIntegers($this->makeDriver(['integer' => true]));
    }

    public function testEncodingWithCustomCharacters()
    {
        $this->runForBytes($this->makeDriver([
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ]));
        $this->runForIntegers($this->makeDriver([
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
            'integer' => true,
        ]));
    }
}
