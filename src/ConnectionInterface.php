<?php

namespace ElfSundae\Laravel\Hashid;

interface ConnectionInterface
{
    /**
     * Encode the given data.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function encode($data);

    /**
     * Decode the given data.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function decode($data);
}
