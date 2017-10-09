<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\Arr;

class Base64Driver implements DriverInterface
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
        return urlsafe_base64_encode($data);
    }

    /**
     * Decode the data.
     *
     * @param  string  $data
     * @return mixed
     */
    public function decode($data)
    {
        $data = urlsafe_base64_decode($data);

        return $this->integer ? (int) $data : $data;
    }
}
