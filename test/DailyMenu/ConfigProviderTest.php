<?php

namespace Test\DailyMenu;


use App\DailyMenu\ConfigProvider;
use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Service\SaveDailyMenusService;
use Psr\Log\LoggerInterface;

class ConfigProviderTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function loadDependencies_GivenContainer_SetDependenciesIntoContainer()
    {
        $container = $this->getContainer();
        $configProvider = new ConfigProvider();
        $configProvider->loadDependencies($container);

        $env = getenv('APPLICATION_ENV');
        putenv('APPLICATION_ENV=prod');
        $this->assertInstanceOf(LoggerInterface::class, $container->get('logger'));
        putenv('APPLICATION_ENV=' . $env);
        $this->assertInstanceOf(CrawlerFactory::class, $container->get(CrawlerFactory::class));
        $this->assertInstanceOf(SaveDailyMenusService::class, $container->get(SaveDailyMenusService::class));
    }
}