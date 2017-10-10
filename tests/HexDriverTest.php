<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HexDriver;
use ElfSundae\Laravel\Hashid\HexIntegerDriver;

class HexDriverTest extends DriverTestCase
{
    protected $driver = HexDriver::class;
    protected $integerDriver = HexIntegerDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf($this->driver, $this->makeDriver());
        $this->assertInstanceOf($this->integerDriver, $this->makeDriver($this->integerDriver));
    }

    public function testEncoding()
    {
        $this->assertEncodedData('Hashid', '486173686964');
        $this->runForBytes();
        $this->assertUniformEncoding(random_bytes(128));

        $this->assertEncodedData(987654321, '3ade68b1', $this->integerDriver);
        $this->runForIntegers($this->integerDriver);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->integerDriver);
    }
}
