<?php

namespace ElfSundae\Laravel\Hashid;

class HexIntegerDriver implements DriverInterface
{
    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return dechex($data);
    }

    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return int
     */
    public function decode($data)
    {
        return (int) hexdec($data);
    }
}
