<?php

namespace App\DailyMenu;

use App\DailyMenu\Action\DailyMenusAction;
use App\DailyMenu\Action\HealthCheckAction;
use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Service\SaveDailyMenusService;
use GuzzleHttp\Client;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ConfigProvider
 * @package App\DailyMenu
 */
class ConfigProvider
{
    public function __invoke(App $app)
    {
        $this->loadConfig($app->getContainer());
        $this->loadRoutes($app);
        $this->loadDependencies($app->getContainer());
    }

    public function loadConfig(ContainerInterface $container)
    {
        $container['settings']['displayErrorDetails'] = (bool)getenv('DEBUG');
    }

    public function loadRoutes(App $app)
    {
        $app->get('/healthcheck', HealthCheckAction::class);
        $app->get('/menus', DailyMenusAction::class);
        $app->get('/menus/{date}', DailyMenusAction::class);
    }

    public function loadDependencies(ContainerInterface $container)
    {
        $container['pdo'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('SET sql_mode=""');
            return $pdo;
        };
        $container['Client'] = function () {
            return new Client();
        };
        $container['DomCrawler'] = function () {
            return new Crawler();
        };
        $container['Twig'] = function (ContainerInterface $container) {
            $view = new Twig(__DIR__ . '/templates', [
                'cache' => false
            ]);

            $router = $container->get('router');
            $uri = $container->get('request')->getUri();
            $view->addExtension(new TwigExtension($router, $uri));
            return $view;
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
            return new RestaurantsDao($container->get('pdo'));
        };
        $container[SaveDailyMenusService::class] = function (ContainerInterface $container) {
            return new SaveDailyMenusService(
                $container->get(RestaurantsDao::class),
                $container->get(MenusDao::class),
                $container->get(CrawlerFactory::class)
            );
        };
        $container[HealthCheckAction::class] = function (ContainerInterface $container) {
            return new HealthCheckAction($container->get('pdo'));
        };
        $container[DailyMenusAction::class] = function (ContainerInterface $container) {
            return new DailyMenusAction($container->get(MenusDao::class), $container->get('Twig'));
        };
    }
}