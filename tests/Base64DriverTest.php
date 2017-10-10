<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base64Driver;
use ElfSundae\Laravel\Hashid\Base64IntegerDriver;

class Base64DriverTest extends DriverTestCase
{
    public function testEncoding()
    {
        $this->runForBytes(Base64Driver::class);
        $this->runForIntegers(Base64IntegerDriver::class);
    }
}
