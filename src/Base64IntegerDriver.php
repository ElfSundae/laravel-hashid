<?php

namespace ElfSundae\Laravel\Hashid;

class Base64IntegerDriver extends Base64Driver
{
    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return int
     */
    public function decode($data)
    {
        $int = (int) ($decoded = parent::decode($data));

        return (string) $int === $decoded ? $int : 0;
    }
}
