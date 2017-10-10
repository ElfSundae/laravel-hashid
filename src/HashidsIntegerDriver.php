<?php

namespace ElfSundae\Laravel\Hashid;

class HashidsIntegerDriver extends HashidsDriver
{
    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return int
     */
    public function decode($data)
    {
        $result = parent::decode($data);

        return (int) reset($result);
    }
}
