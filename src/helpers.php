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

if (! function_exists('urlsafe_base64_encode')) {
    /**
     * Encodes the given data with base64, and returns an URL-safe string.
     *
     * @see https://github.com/ElfSundae/laravel-helper
     *
     * @param  string  $data
     * @return string
     */
    function urlsafe_base64_encode($data)
    {
        return strtr(base64_encode($data), ['+' => '-', '/' => '_', '=' => '']);
    }
}

if (! function_exists('urlsafe_base64_decode')) {
    /**
     * Decodes a base64 encoded data.
     *
     * @see https://github.com/ElfSundae/laravel-helper
     *
     * @param  string  $data
     * @param  bool  $strict
     * @return string
     */
    function urlsafe_base64_decode($data, $strict = false)
    {
        return base64_decode(strtr($data.str_repeat('=', (4 - strlen($data) % 4)), '-_', '+/'), $strict);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     * For Lumen compatible.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath().DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
