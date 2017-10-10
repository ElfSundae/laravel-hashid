<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hashid Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the hashid connections below you wish
    | to use as your default connection for all hashid work. Of course
    | you may use many connections at once using the Hashid manager.
    |
    */

    'default' => 'base62',

    /*
    |--------------------------------------------------------------------------
    | Hashid Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the hashid connections setup for your application.
    | Of course, examples of configuring each hashid driver is shown below
    | to make development simple. You are free to add more.
    |
    */

    'connections' => [

        'base62' => [
            'driver' => 'base62',
            'characters' => env('HASHID_BASE62_CHARACTERS', '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),
        ],

        'base62.integer' => [
            'driver' => 'base62.integer',
            'characters' => env('HASHID_BASE62_CHARACTERS', '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),
        ],

        'base64' => [
            'driver' => 'base64',
        ],

        'base64.integer' => [
            'driver' => 'base64.integer',
        ],

        'hashids' => [
            'driver' => 'hashids',
            'salt' => env('HASHID_HASHIDS_SALT', ''),
            'min_length' => env('HASHID_HASHIDS_MIN_LENGTH', 0),
            'alphabet' => env('HASHID_HASHIDS_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
        ],

        'hashids.hex' => [
            'driver' => 'hashids.hex',
            'salt' => env('HASHID_HASHIDS_SALT', ''),
            'min_length' => env('HASHID_HASHIDS_MIN_LENGTH', 0),
            'alphabet' => env('HASHID_HASHIDS_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
        ],

        'hex' => [
            'driver' => 'hex',
        ],

        'hex.integer' => [
            'driver' => 'hex.integer',
        ],

        'optimus' => [
            'driver' => 'optimus',
            'prime' => env('HASHID_OPTIMUS_PRIME'),
            'inverse' => env('HASHID_OPTIMUS_INVERSE'),
            'random' => env('HASHID_OPTIMUS_RANDOM', 0),
        ],

    ],

];
