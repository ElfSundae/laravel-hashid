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
        $decoded = parent::decode($data);

        return 1 === count($decoded) ? reset($decoded) : 0;
    }
}
