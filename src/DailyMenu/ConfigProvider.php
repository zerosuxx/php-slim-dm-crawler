<?php

namespace App\DailyMenu;

use App\DailyMenu\Action\DailyMenusAction;
use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Service\SaveDailyMenusService;
use App\Skeleton\Log\FileLogger;
use GuzzleHttp\Client;
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
        $this->loadRoutes($app);
        $this->loadDependencies($app->getContainer());
    }

    public function loadRoutes(App $app)
    {
        $app->get('/menus', DailyMenusAction::class);
        $app->get('/menus/{startDate}', DailyMenusAction::class);
        $app->get('/menus/{startDate}/{endDate}', DailyMenusAction::class);
    }

    public function loadDependencies(ContainerInterface $container)
    {
        $container['client'] = function () {
            return new Client();
        };

        $container['domCrawler'] = function () {
            return new Crawler();
        };

        $container['errorLogger'] = function (ContainerInterface $container) {
            $logDir = $container['rootDir'] . '/logs';
            return new FileLogger($logDir, 'daily_menu_app_error');
        };

        $container['twig'] = function (ContainerInterface $container) {
            $view = new Twig(__DIR__ . '/Templates', [
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
                $container->get('client'),
                $container->get('domCrawler')
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
                $container->get(CrawlerFactory::class),
                $container->get('errorLogger')
            );
        };
        $container[DailyMenusAction::class] = function (ContainerInterface $container) {
            return new DailyMenusAction($container->get(MenusDao::class), $container->get('twig'));
        };
    }
}