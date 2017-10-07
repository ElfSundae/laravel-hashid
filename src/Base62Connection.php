<?php

namespace ElfSundae\Laravel\Hashid;

use Tuupola\Base62;
use Illuminate\Support\Arr;

class Base62Connection extends Connection
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
     * @param  \Illuminate\Foundation\Application  $app
     * @param  array  $config
     */
    public function __construct($app, array $config = [])
    {
        parent::__construct($app, $config);

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
        return $this->base62->encode($data);
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
