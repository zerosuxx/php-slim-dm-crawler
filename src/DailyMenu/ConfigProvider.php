<?php

namespace App\DailyMenu;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Service\SaveDailyMenuService;
use GuzzleHttp\Client;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ConfigProvider
 * @package App\DailyMenu
 */
class ConfigProvider
{
    public function __invoke(App $app)
    {
        $this->loadDependencies($app->getContainer());
        $this->loadConfig($app->getContainer());
    }

    public function loadConfig(ContainerInterface $container)
    {
        $container['app_config'] = [
            'crawler_aliases' => [
                'Bonnie' => BonnieCrawler::class
            ]
        ];
    }

    public function loadDependencies(ContainerInterface $container)
    {
        $container['pdo'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
        $container['Client'] = function () {
            return new Client();
        };
        $container['DomCrawler'] = function () {
            return new Crawler();
        };
        $container[CrawlerFactory::class] = function (ContainerInterface $container) {
            return new CrawlerFactory(
                $container->get(RestaurantsDao::class),
                $container->get('Client'),
                $container->get('DomCrawler')
            );
        };
        $container[MenusDao::class] = function (ContainerInterface $container) {
            return new MenusDao($container->get('pdo'));
        };
        $container[RestaurantsDao::class] = function (ContainerInterface $container) {
            $appConfig = $container->get('app_config');
            return new RestaurantsDao($container->get('pdo'), $appConfig['crawler_aliases']);
        };
        $container[SaveDailyMenuService::class] = function (ContainerInterface $container) {
            return new SaveDailyMenuService(
                $container->get(RestaurantsDao::class),
                $container->get(MenusDao::class),
                $container->get(CrawlerFactory::class)
            );
        };
    }
}