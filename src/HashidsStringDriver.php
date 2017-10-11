<?php

namespace ElfSundae\Laravel\Hashid;

class HashidsStringDriver extends HashidsHexDriver
{
    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return parent::encode(bin2hex($data));
    }

    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function decode($data)
    {
        return hex2bin(parent::decode($data));
    }
}
