<?php

namespace Test\App\Service;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use App\DailyMenu\Service\SaveDailyMenusService;
use Test\App\DailyMenuTestCase;

/**
 * Class SaveDailyMenuServiceTest
 * @package Test\App\Service
 */
class SaveDailyMenuServiceTest extends DailyMenuTestCase
{
    /**
     * @test
     */
    public function saveDailyMenus_GivenRestaurantsDao_SaveDailyMenusToDatabase()
    {
        $restaurantId = 1;
        $foods = 'test foods';
        $price = 1000;
        $date = '2018-09-17';
        $dateTime = new \DateTime($date);
        $this->truncateTable('menus');
        $restaurantsDaoMock = $this->createMock(RestaurantsDao::class);
        $restaurantsDaoMock
            ->expects($this->once())
            ->method('getDailyRestaurants')
            ->willReturn([
                new Restaurant('Bonnie', '', 1),
                new Restaurant('Test', '', 2)
            ]);

        $bonnieCrawlerMock = $this->createMock(BonnieCrawler::class);
        $bonnieCrawlerMock
            ->expects($this->any())
            ->method('getDailyMenu')
            ->willReturn((new Menu($restaurantId, [$foods], $price, $dateTime)));

        $crawlerFactoryMock = $this->createMock(CrawlerFactory::class);
        $crawlerFactoryMock
            ->expects($this->any())
            ->method('getCrawlerFromRestaurantName')
            ->willReturn($bonnieCrawlerMock);

        $service = new SaveDailyMenusService(
            $restaurantsDaoMock,
            $this->getService(MenusDao::class),
            $crawlerFactoryMock
        );

        $service->saveDailyMenus($dateTime);

        $menuData = $this->getPDO()->query('SELECT * FROM menus WHERE id = 1')->fetch(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, $menuData['id']);
        $this->assertEquals($restaurantId, $menuData['restaurant_id']);
        $this->assertEquals($foods, $menuData['foods']);
        $this->assertEquals($price, $menuData['price']);
        $this->assertEquals($date, $menuData['date']);

    }
}