<?php

namespace ElfSundae\Laravel\Hashid;

use Hashids\Hashids;
use Illuminate\Support\Arr;

class HashidsDriver implements DriverInterface
{
    /**
     * The Hashids instance.
     *
     * @var \Hashids\Hashids
     */
    protected $hashids;

    /**
     * Create a new Hashids driver instance.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $this->hashids = new Hashids(
            Arr::get($config, 'salt', ''),
            Arr::get($config, 'min_length', 0),
            Arr::get($config, 'alphabet', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
        );
    }

    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return $this->hashids->encode($data);
    }

    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function decode($data)
    {
        return $this->hashids->decode($data);
    }
}
