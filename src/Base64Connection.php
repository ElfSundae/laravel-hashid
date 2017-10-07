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
        return strtr(base64_encode($data), ['+' => '-', '/' => '_', '=' => '']);
    }

    /**
     * Decodes a base64 encoded data.
     *
     * @param  string  $data
     * @return string
     */
    public function decode($data)
    {
        return base64_decode(strtr($data.str_repeat('=', (4 - strlen($data) % 4)), '-_', '+/'));
    }
}
