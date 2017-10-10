<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HexDriver;
use ElfSundae\Laravel\Hashid\HexIntegerDriver;

class HexDriverTest extends DriverTestCase
{
    public function testEncoding()
    {
        $this->runForBytes(HexDriver::class);
        $this->runForIntegers(HexIntegerDriver::class);
    }
}
