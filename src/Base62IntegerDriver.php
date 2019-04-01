<?php

namespace ElfSundae\Laravel\Hashid;

class Base62IntegerDriver extends Base62Driver
{
    /**
     * Encode the data.
     *
     * @param  int  $data
     * @return string
     */
    public function encode($data)
    {
        return $this->base62->encodeInteger($data);
    }

    /**
     * Decode the data.
     *
     * @param  string  $data
     * @return int
     */
    public function decode($data)
    {
        return $this->base62->decodeInteger($data);
    }
}
