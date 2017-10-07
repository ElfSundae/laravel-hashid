<?php

namespace ElfSundae\Laravel\Hashid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ElfSundae\Laravel\Hashid\HashidManager
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
