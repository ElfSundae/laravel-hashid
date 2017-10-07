<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use ElfSundae\Laravel\Hashid\Base64Connection;

class Base64ConnectionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base64Connection::class, new Base64Connection);
    }

    public function testEncodeAndDecode()
    {
        $conn = new Base64Connection;
        $this->assertSame('TGFyYXZlbA', $conn->encode('Laravel'));
        $this->assertSame('Laravel', $conn->decode('TGFyYXZlbA'));
    }
}
