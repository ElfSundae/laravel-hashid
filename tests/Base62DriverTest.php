<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base62Driver;
use ElfSundae\Laravel\Hashid\Base62IntegerDriver;

class Base62DriverTest extends DriverTestCase
{
    protected $driver = Base62Driver::class;
    protected $integerDriver = Base62IntegerDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf($this->driver, $this->makeDriver());
        $this->assertInstanceOf($this->integerDriver, $this->makeDriver($this->integerDriver));
    }

    public function testEncoding()
    {
        $this->assertEncodedData('Hashid', 'Mb6pKATc');
        $this->runForBytes();
        $this->assertUniformEncoding(random_bytes(128));

        $this->assertEncodedData(987654321, '14q60P', $this->integerDriver);
        $this->runForIntegers($this->integerDriver);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->integerDriver);
    }

    public function testEncodingWithCustomCharacters()
    {
        $config = [
            'characters' => str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ];
        $this->runForBytes();
        $this->assertUniformEncoding(random_bytes(128), $config);
        $this->runForIntegers($this->integerDriver, $config);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->integerDriver, $config);
    }
}
