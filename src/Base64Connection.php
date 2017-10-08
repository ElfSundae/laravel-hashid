<?php

namespace ElfSundae\Laravel\Hashid;

use Illuminate\Support\Arr;

class Base64Connection implements ConnectionInterface
{
    /**
     * Indicates encoding to integer.
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
        $this->integer = Arr::get($config, 'integer', false);
    }

    /**
     * Encode the given string.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return urlsafe_base64_encode($data);
    }

    /**
     * Decode the base64 encoded data.
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
