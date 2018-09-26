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
        $container['rootDir'] = sys_get_temp_dir();
        $configProvider = new ConfigProvider();
        $configProvider->loadDependencies($container);
        $this->assertInstanceOf(LoggerInterface::class, $container->get('errorLogger'));
        $this->assertInstanceOf(CrawlerFactory::class, $container->get(CrawlerFactory::class));
        $this->assertInstanceOf(SaveDailyMenusService::class, $container->get(SaveDailyMenusService::class));
    }
}