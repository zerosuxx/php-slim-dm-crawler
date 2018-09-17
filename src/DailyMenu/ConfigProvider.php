<?php

namespace App\DailyMenu;

use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;

/**
 * Class ConfigProvider
 * @package App\DailyMenu
 */
class ConfigProvider
{
    public function __invoke(App $app)
    {
        $this->loadDependencies($app->getContainer());
    }

    public function loadDependencies(ContainerInterface $container)
    {
        $container['pdo'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
    }
}