<?php

if (! function_exists('hashid')) {
    /**
     * Get a hashid connection instance.
     *
     * @param  string|null  $name
     * @return mixed
     */
    function hashid($name = null)
    {
        return app('hashid')->connection($name);
    }
}

if (! function_exists('hashid_encode')) {
    /**
     * Encode the given data using hashid.
     *
     * @param  mixed  $data
     * @param  string|null  $name
     * @return mixed
     */
    function hashid_encode($data, $name = null)
    {
        return hashid($name)->encode($data);
    }
}

if (! function_exists('hashid_decode')) {
    /**
     * Decode the given data using hashid.
     *
     * @param  mixed  $data
     * @param  string|null  $name
     * @return mixed
     */
    function hashid_decode($data, $name = null)
    {
        return hashid($name)->decode($data);
    }
}
