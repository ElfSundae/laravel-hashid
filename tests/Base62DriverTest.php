<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base62Driver;
use ElfSundae\Laravel\Hashid\Base62IntegerDriver;

class Base62DriverTest extends DriverTestCase
{
    protected $driver = Base62Driver::class;
    protected $intDriver = Base62IntegerDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base62Driver::class, $this->makeDriver());
    }

    public function testEncoding()
    {
        $this->runForBytes();
        $this->assertUniformEncoding(random_bytes(128));
        $this->runForIntegers($this->intDriver);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->intDriver);
    }

    public function testEncodingWithCustomCharacters()
    {
        $config = [
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ];
        $this->runForBytes($config);
        $this->assertUniformEncoding(random_bytes(128), $config);
        $this->runForIntegers($this->intDriver, $config);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->intDriver, $config);
    }
}
