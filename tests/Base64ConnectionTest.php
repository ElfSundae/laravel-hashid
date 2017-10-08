<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base64Connection;

class Base64ConnectionTest extends ConnectionTestCase
{
    protected $connection = Base64Connection::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base64Connection::class, $this->getConnection());
    }

    public function testEncodingString()
    {
        $connection = $this->getConnection();
        $this->assertSame('TGFyYXZlbA', $connection->encode('Laravel'));
        $this->assertSame('Laravel', $connection->decode('TGFyYXZlbA'));
    }

    public function testEncodingInteger()
    {
        $connection = $this->getConnection([
            'integer' => true,
        ]);
        $this->assertSame('MTIzNDU2Nw', $connection->encode(1234567));
        $this->assertSame('MTIzNDU2Nw', $connection->encode('1234567'));
        $this->assertSame(1234567, $connection->decode('MTIzNDU2Nw'));
    }
}
