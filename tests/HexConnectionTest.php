<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\HexConnection;

class HexConnectionTest extends ConnectionTestCase
{
    protected $connection = HexConnection::class;

    public function testInstantiation()
    {
        $this->assertInstanceOf(HexConnection::class, $this->getConnection());
    }

    public function testEncoding()
    {
        $connection = $this->getConnection();
        $this->assertSame('12d687', $connection->encode(1234567));
        $this->assertSame('12d687', $connection->encode('1234567'));
        $this->assertSame(1234567, $connection->decode('12d687'));
    }
}
