<?php

namespace ElfSundae\Laravel\Hashid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \ElfSundae\Laravel\Hashid\DriverInterface connection(?string $name = null)
 * @method static \ElfSundae\Laravel\Hashid\DriverInterface driver(?string $name = null)
 * @method static string getDefaultConnection()
 * @method static \ElfSundae\Laravel\Hashid\HashidManager setDefaultConnection(string $name)
 * @method static mixed encode(mixed $data)
 * @method static mixed decode(mixed $data)
 *
 * @see \ElfSundae\Laravel\Hashid\HashidManager
 * @see \ElfSundae\Laravel\Hashid\DriverInterface
 */
class Hashid extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hashid';
    }
}
