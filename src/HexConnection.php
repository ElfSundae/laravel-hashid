<?php

namespace ElfSundae\Laravel\Hashid;

class HexConnection implements ConnectionInterface
{
    /**
     * Encode the given integer.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return dechex((int) $data);
    }

    /**
     * Decode the hex string.
     *
     * @param  mixed  $data
     * @return int
     */
    public function decode($data)
    {
        return hexdec($data);
    }
}
