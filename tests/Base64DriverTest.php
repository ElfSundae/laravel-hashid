<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base64Driver;
use ElfSundae\Laravel\Hashid\Base64IntegerDriver;

class Base64DriverTest extends DriverTestCase
{
    protected $driver = Base64Driver::class;
    protected $integerDriver = Base64IntegerDriver::class;

    public function testEncoding()
    {
        $this->runForBytes();
        $this->assertUniformEncoding(random_bytes(128));
        $this->runForIntegers($this->integerDriver);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->integerDriver);
    }
}
