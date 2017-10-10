<?php

namespace ElfSundae\Laravel\Hashid;

class HashidsHexDriver extends HashidsDriver
{
    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return $this->hashids->encodeHex($data);
    }

    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function decode($data)
    {
        return $this->hashids->decodeHex($data);
    }
}
