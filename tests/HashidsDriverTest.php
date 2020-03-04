<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HashidsDriver;
use ElfSundae\Laravel\Hashid\HashidsHexDriver;
use ElfSundae\Laravel\Hashid\HashidsIntegerDriver;
use ElfSundae\Laravel\Hashid\HashidsStringDriver;

class HashidsDriverTest extends DriverTestCase
{
    protected $driver = HashidsDriver::class;
    protected $hexDriver = HashidsHexDriver::class;
    protected $integerDriver = HashidsIntegerDriver::class;
    protected $stringDriver = HashidsStringDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf($this->driver, $this->makeDriver());
        $this->assertInstanceOf($this->hexDriver, $this->makeDriver($this->hexDriver));
        $this->assertInstanceOf($this->integerDriver, $this->makeDriver($this->integerDriver));
        $this->assertInstanceOf($this->stringDriver, $this->makeDriver($this->stringDriver));
    }

    public function testEncoding()
    {
        $this->assertEncodedData([1, 2, 3], 'o2fXhV');
        $randomIntegers = array_map(function () {
            return random_int(0, PHP_INT_MAX);
        }, array_fill(0, 10, null));
        $this->assertReversible($randomIntegers);
        $this->assertUniformEncoding($randomIntegers);

        $this->assertEncodedData('507f1f77bcf86cd799439011', 'y42LW46J9luq3Xq9XMly', $this->hexDriver);
        $this->assertReversible(bin2hex(random_bytes(128)), $this->hexDriver);
        $this->assertUniformEncoding(bin2hex(random_bytes(128)), $this->hexDriver);

        $this->assertEncodedData(1, 'jR', $this->integerDriver);
        $this->runForIntegers($this->integerDriver);
        $this->assertUniformEncoding(random_int(0, PHP_INT_MAX), $this->integerDriver);
        $integerDriver = $this->makeDriver($this->integerDriver);
        $encoded = $integerDriver->encode([1, 2, 3]);
        $this->assertSame(0, $integerDriver->decode($encoded));

        $this->assertEncodedData('Hashid', 'mWxDrOkZ49', $this->stringDriver);
        $this->runForBytes($this->stringDriver);
        $this->assertUniformEncoding(random_bytes(128), $this->stringDriver);
    }
}
