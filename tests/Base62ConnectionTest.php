<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base62Connection;

class Base62ConnectionTest extends ConnectionTestCase
{
    protected $connection = Base62Connection::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base62Connection::class, $this->getConnection());
    }

    public function testEncodingData()
    {
        $connection = $this->getConnection();
        $this->assertSame('1aSwDGDVUq', $connection->encode('Laravel'));
        $this->assertSame('Laravel', $connection->decode('1aSwDGDVUq'));
    }

    public function testEncodingInteger()
    {
        $connection = $this->getConnection([
            'integer' => true,
        ]);
        $this->assertSame('14q60P', $connection->encode(987654321));
        $this->assertSame('14q60P', $connection->encode('987654321'));
        $this->assertSame(987654321, $connection->decode('14q60P'));
    }

    public function testEncodingWithCustomCharacters()
    {
        $connection = $this->getConnection([
            'characters' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        ]);
        $this->assertSame('1AsWdgdvuQ', $connection->encode('Laravel'));
        $this->assertSame('Laravel', $connection->decode('1AsWdgdvuQ'));
    }

    public function testEncodingWithEmptyCharacters()
    {
        $connection = $this->getConnection([
            'characters' => '',
        ]);
        $this->assertSame('1aSwDGDVUq', $connection->encode('Laravel'));
        $this->assertSame('Laravel', $connection->decode('1aSwDGDVUq'));
    }
}
