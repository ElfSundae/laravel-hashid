<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\Arr;

class HexDriver implements DriverInterface
{
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
        return $this->integer ? dechex($data) : bin2hex($data);
    }

    /**
     * Decode the data.
     *
     * @param  string  $data
     * @return mixed
     */
    public function decode($data)
    {
        return $this->integer ? hexdec($data) : hex2bin($data);
    }
}
