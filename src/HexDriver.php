<?php

namespace ElfSundae\Laravel\Hashid;

class HexDriver implements DriverInterface
{
    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return bin2hex($data);
    }

    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function decode($data)
    {
        return hex2bin($data);
    }
}
