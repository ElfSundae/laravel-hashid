<?php

namespace ElfSundae\Laravel\Hashid\Test;

use PHPUnit\Framework\TestCase;
use ElfSundae\Laravel\Hashid\Base64Connection;

class Base64ConnectionTest extends TestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(Base64Connection::class, new Base64Connection);
    }

    public function testEncodeAndDecode()
    {
        $connection = new Base64Connection;
        $this->assertSame('TGFyYXZlbA', $connection->encode('Laravel'));
        $this->assertSame('Laravel', $connection->decode('TGFyYXZlbA'));
    }
}
