<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Mockery as m;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExample()
    {
        $this->assertTrue(true);
    }
}
