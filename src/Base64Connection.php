<?php

namespace ElfSundae\Laravel\Hashid;

class Base64Connection implements ConnectionInterface
{
    /**
     * Encodes the given data with base64, and returns an URL-safe string.
     *
     * @param  string  $data
     * @return string
     */
    public function encode($data)
    {
        return urlsafe_base64_encode($data);
    }

    /**
     * Decodes a base64 encoded data.
     *
     * @param  string  $data
     * @return string
     */
    public function decode($data)
    {
        return urlsafe_base64_decode($data);
    }
}
