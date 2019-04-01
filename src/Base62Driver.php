<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\Arr;
use Tuupola\Base62\GmpEncoder;
use Tuupola\Base62\PhpEncoder;

class Base62Driver implements DriverInterface
{
    /**
     * The Base62 instance.
     *
     * @var \Tuupola\Base62\GmpEncoder|\Tuupola\Base62\PhpEncoder
     */
    protected $base62;

    /**
     * Create a new Base62 driver instance.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $options = Arr::only($config, ['characters']);

        $this->base62 = extension_loaded('gmp')
            ? new GmpEncoder($options) : new PhpEncoder($options);
    }

    /**
     * Encode the data.
     *
     * @param  string  $data
     * @return string
     */
    public function encode($data)
    {
        return $this->base62->encode($data);
    }

    /**
     * Decode the data.
     *
     * @param  string  $data
     * @return string
     */
    public function decode($data)
    {
        return $this->base62->decode($data);
    }
}
