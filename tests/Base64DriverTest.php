<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base64Driver;

class Base64DriverTest extends DriverTestCase
{
    protected $driver = Base64Driver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base64Driver::class, $this->makeDriver());
    }

    public function testEncoding()
    {
        $this->callEncodingString($this->makeDriver(), 10);
        $this->callEncodingInteger($integerDriver = $this->makeDriver(['integer' => true]), 10);
        $this->callEncodingMaxInteger($integerDriver);
    }
}
