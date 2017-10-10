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
        return (int) parent::decode($data);
    }
}
