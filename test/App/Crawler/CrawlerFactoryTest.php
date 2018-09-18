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
        $restaurant = new Restaurant('Test', 'http://test.test');

        $restaurantsDaoMock = $this->createMock(RestaurantsDao::class);
        $restaurantsDaoMock
            ->expects($this->once())
            ->method('getRestaurant')
            ->willReturn($restaurant->withCrawlerClass(BonnieCrawler::class));

        $factory = new CrawlerFactory($restaurantsDaoMock, new Client(), new Crawler());
        $crawler = $factory->createCrawlerFromName('Bonnie');
        $this->assertInstanceOf(BonnieCrawler::class, $crawler);
    }
}