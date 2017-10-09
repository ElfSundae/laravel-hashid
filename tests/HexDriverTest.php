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
        $this->callEncodingInteger($integerDriver = $this->makeDriver(['integer' => true]), 10);
        $this->callEncodingMaxInteger($integerDriver);
    }
}
