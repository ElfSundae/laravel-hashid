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
     * Indicates integer encoding.
     *
     * @var bool
     */
    protected $integer = false;

    /**
     * Create a new hashid driver instance.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $options = Arr::only($config, ['characters']);
        if (extension_loaded('gmp')) {
            $this->base62 = new GmpEncoder($options);
        } else {
            $this->base62 = new PhpEncoder($options);
        }

        $this->integer = Arr::get($config, 'integer', $this->integer);
    }

    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return $this->base62->encode($data, $this->integer);
    }

    /**
     * Decode the data.
     *
     * @param  string  $data
     * @return mixed
     */
    public function decode($data)
    {
        return $this->base62->decode($data, $this->integer);
    }
}
