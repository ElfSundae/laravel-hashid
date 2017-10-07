<?php

namespace ElfSundae\Laravel\Hashid\Test;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Container\Container;
use ElfSundae\Laravel\Hashid\Base62Connection;

class Base62ConnectionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(Base62Connection::class, $this->getConnection());
    }

    public function testEncodeAndDecodeData()
    {
        $connection = $this->getConnection();
        $this->assertSame('1aSwDGDVUq', $connection->encode('Laravel'));
        $this->assertSame('Laravel', $connection->decode('1aSwDGDVUq'));
        $this->assertSame('14q60P', $connection->encode(987654321));
        $this->assertSame(987654321, hexdec(bin2hex($connection->decode('14q60P'))));
    }

    public function testEncodeAndDecodeInteger()
    {
        $connection = $this->getConnection(['integer' => true]);
        $this->assertSame('14q60P', $connection->encode(987654321));
        $this->assertSame(987654321, $connection->decode('14q60P'));
    }

    public function testEncodeAndDecodeWithCustomCharacters()
    {
        $connection = $this->getConnection([
            'characters' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'integer' => true,
        ]);
        $this->assertSame('14Q60p', $connection->encode(987654321));
        $this->assertSame(987654321, $connection->decode('14Q60p'));
    }

    protected function getConnection($config = [], $app = null)
    {
        if (is_null($app)) {
            $app = m::mock(Container::class);
        }

        return new Base62Connection($app, $config);
    }
}
