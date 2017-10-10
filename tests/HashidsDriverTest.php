<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HashidsDriver;
use ElfSundae\Laravel\Hashid\HashidsHexDriver;
use ElfSundae\Laravel\Hashid\HashidsIntegerDriver;

class HashidsDriverTest extends DriverTestCase
{
    protected $driver = HashidsDriver::class;
    protected $hexDriver = HashidsHexDriver::class;
    protected $integerDriver = HashidsIntegerDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf($this->driver, $this->makeDriver());
        $this->assertInstanceOf($this->hexDriver, $this->makeDriver($this->hexDriver));
        $this->assertInstanceOf($this->integerDriver, $this->makeDriver($this->integerDriver));
    }

    public function testEncoding()
    {
        $this->assertEncodedData([1, 2, 3], 'o2fXhV');
        $this->assertEncodedData('507f1f77bcf86cd799439011', 'y42LW46J9luq3Xq9XMly', $this->hexDriver);
        $this->assertEncodedData(1, 'jR', $this->integerDriver);
    }
}
