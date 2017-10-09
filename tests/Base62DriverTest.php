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
        $this->callEncodingString($this->makeDriver(), 10);
        $this->callEncodingInteger($integerDriver = $this->makeDriver(['integer' => true]), 10);
        $this->callEncodingMaxInteger($integerDriver);
    }

    public function testEncodingWithCustomCharacters()
    {
        $config = [
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ];

        $this->callEncodingString($this->makeDriver($config));
        $this->callEncodingInteger($integerDriver = $this->makeDriver(['integer' => true] + $config));
        $this->callEncodingMaxInteger($integerDriver);
    }
}
