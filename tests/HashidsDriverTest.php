<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HashidsDriver;

class HashidsDriverTest extends DriverTestCase
{
    protected $driver = HashidsDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(HashidsDriver::class, $this->makeDriver());
    }
}
