<?php

namespace Test\App\Crawler;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Entity\Restaurant;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CrawlerFactoryTest
 * @package Test\App\Crawler
 */
class CrawlerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createCrawlerFromName_ReturnsNewCrawlerInstance()
    {
        $restaurantsDaoMock = $this->createMock(RestaurantsDao::class);
        $restaurantsDaoMock
            ->expects($this->once())
            ->method('getRestaurant')
            ->willReturn($this->createMock(Restaurant::class));

        $factory = new CrawlerFactory($restaurantsDaoMock, new Client(), new Crawler());
        $crawler = $factory->getCrawlerFromRestaurantName('Bonnie');
        $this->assertInstanceOf(BonnieCrawler::class, $crawler);
    }

    /**
     * @test
     */
    public function getCrawlerFromRestaurantName_GivenNotExistsRestaurantName_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $factory = new CrawlerFactory($this->createMock(RestaurantsDao::class), new Client(), new Crawler());
        $factory->getCrawlerFromRestaurantName('not exists');
    }


}