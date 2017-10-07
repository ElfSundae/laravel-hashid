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

    'default' => '',

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

        'base64' => [
            'driver' => 'base64',
        ],

        'base62' => [
            'driver' => 'base62',
            'alphabet' => env('HASHID_BASE62_ALPHABET', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        ],

    ],

];
