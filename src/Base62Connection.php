<?php

namespace ElfSundae\Laravel\Hashid;

use Tuupola\Base62;
use Illuminate\Support\Arr;

class Base62Connection implements ConnectionInterface
{
    /**
     * The Base62 instance.
     *
     * @var \Tuupola\Base62
     */
    protected $base62;

    /**
     * Indicates encoding to and from integer.
     *
     * @var bool
     */
    protected $integer = false;

    /**
     * Create a new hashid connection instance.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $this->base62 = new Base62(
            array_filter(Arr::only($config, ['characters']))
        );

        $this->integer = Arr::get($config, 'integer', false);
    }

    /**
     * Encode the given data.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function encode($data)
    {
        return $this->base62->encode($this->integer ? (int) $data : $data);
    }

    /**
     * Decode the given data.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function decode($data)
    {
        return $this->base62->decode($data, $this->integer);
    }
}
