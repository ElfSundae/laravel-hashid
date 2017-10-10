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
        $this->runForBytes();
        $this->runForIntegers($this->makeDriver(['integer' => true]));
    }
}
