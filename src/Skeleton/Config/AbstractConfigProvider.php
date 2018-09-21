<?php

namespace App\Skeleton\Config;

use Psr\Container\ContainerInterface;
use Slim\App;

/**
 * Class AbstractConfigProvider
 * @package App\Skeleton\Config
 */
abstract class AbstractConfigProvider
{
    public function __invoke(App $app)
    {
        $this->loadRoutes($app);
        $this->loadDependencies($app->getContainer());
    }

    abstract public function loadRoutes(App $app);

    abstract public function loadDependencies(ContainerInterface $container);
}