<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HexDriver;

class HexDriverTest extends DriverTestCase
{
    protected $driver = HexDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(HexDriver::class, $this->makeDriver());
    }

    public function testEncoding()
    {
        $this->runForBytes();
        $this->runForIntegers($this->makeDriver(['integer' => true]));
    }
}
