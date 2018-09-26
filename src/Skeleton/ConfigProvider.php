<?php

namespace App\Skeleton;

use App\Skeleton\Action\HealthCheckAction;
use App\Skeleton\Config\AbstractConfigProvider;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;

class ConfigProvider extends AbstractConfigProvider
{

    public function loadRoutes(App $app)
    {
        $app->get('/healthcheck', HealthCheckAction::class);
    }

    public function loadDependencies(ContainerInterface $container)
    {
        $container['settings']['displayErrorDetails'] = (bool)getenv('DEBUG');

        $container['pdo'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('SET sql_mode=""');
            return $pdo;
        };

        $container[HealthCheckAction::class] = function (ContainerInterface $container) {
            return new HealthCheckAction($container->get('pdo'));
        };
    }
}