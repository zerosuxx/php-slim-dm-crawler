<?php

namespace App;

use Slim\App;

/**
 * Class AppBuilder
 */
class AppBuilder
{

    /**
     * @var callable[]
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
        $this->attachProviders($app);
        return $app;
    }

    /**
     * @param App $app
     */
    public function attachProviders(App $app)
    {
        foreach ($this->providers as $provider) {
            $provider($app);
        }
    }

}