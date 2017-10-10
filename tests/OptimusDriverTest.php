<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Jenssegers\Optimus\Energon;
use ElfSundae\Laravel\Hashid\OptimusDriver;

class OptimusDriverTest extends DriverTestCase
{
    protected $driver = OptimusDriver::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(OptimusDriver::class, $this->makeDriver($this->getConfig()));
    }

    public function testExceptionCreatingWithEmptyConfig()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('prime and inverse must be specified.');
        $this->makeDriver();
    }

    public function testExceptionCreatingWithInvalidPrime()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('prime and inverse must be specified.');
        $this->makeDriver(['prime' => null]);
    }

    public function testExceptionCreatingWithInvalidInverse()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('prime and inverse must be specified.');
        $this->makeDriver(['inverse' => null]);
    }

    public function testEncoding()
    {
        $this->runForIntegersWith(0, 300, $this->getConfig());
    }

    protected function getConfig(array $config = [])
    {
        return array_merge(
            array_combine(['prime', 'inverse', 'random'], Energon::generate()),
            $config
        );
    }
}
