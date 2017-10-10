<?php

namespace ElfSundae\Laravel\Hashid;

class Base62IntegerDriver extends Base62Driver
{
    /**
     * Indicates integer encoding.
     *
     * @var bool
     */
    protected $integer = true;
}
