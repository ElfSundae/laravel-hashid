<?php

namespace ElfSundae\Laravel\Hashid;

abstract class Connection implements ConnectionInterface
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The hashid connection configuration options.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Create a new hashid connection instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  array  $config
     */
    public function __construct($app, array $config)
    {
        $this->app = $app;
        $this->config = $config;
    }
}
