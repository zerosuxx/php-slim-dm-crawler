<?php

namespace App;

use Slim\App;

/**
 * Class AppBuilder
 */
class AppBuilder
{

    /**
     * @var array
     */
    private $providers = [];

    /**
     * @param callable $provider
     * @return self
     */
    public function addProvider(callable $provider)
    {
        $this->providers[] = $provider;
        return $this;
    }

    /**
     * @param array $container
     * @return App
     */
    public function buildApp($container = []): App
    {
        $app = new App($container);
        foreach ($this->providers as $provider) {
            $provider($app);
        }
        return $app;
    }

}