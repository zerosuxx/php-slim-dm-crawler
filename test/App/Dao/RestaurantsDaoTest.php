<?php

namespace Test\App\Dao;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Entity\Restaurant;
use Test\App\DailyMenuTestCase;

class RestaurantsDaoTest extends DailyMenuTestCase
{

    /**
     * @var \PDO
     */
    private $pdo;

    protected function setUp()
    {
        $this->pdo = $this->getPDO();
        $this->truncateTable('restaurants');
        $this->pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test", "http://test.test")'
        );
    }

    /**
     * @test
     */
    public function getRestaurant_ReturnsRestaurant()
    {
        $dao = new RestaurantsDao($this->pdo, ['Test' => BonnieCrawler::class]);

        $restaurant = $dao->getRestaurant('Test');
        $this->assertInstanceOf(Restaurant::class, $restaurant);
        $this->assertEquals(1, $restaurant->getId());
        $this->assertEquals('Test', $restaurant->getName());
        $this->assertEquals('http://test.test', $restaurant->getUrl());
        $this->assertEquals(BonnieCrawler::class, $restaurant->getCrawlerClass());
    }

    /**
     * @test
     */
    public function getRestaurant_WithoutCrawlerAliases_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $dao = new RestaurantsDao($this->pdo, []);

        $dao->getRestaurant('NotExists');
    }

    /**
     * @test
     */
    public function getRestaurants_ReturnsRestaurants()
    {
        $this->pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test2", "http://test.test")'
        );
        $dao = new RestaurantsDao($this->pdo, [
            'Test' => BonnieCrawler::class,
            'Test2' => BonnieCrawler::class
        ]);

        $restaurants = $dao->getRestaurants();
        $this->assertCount(2, $restaurants);
        $this->assertEquals(1, $restaurants[0]->getId());
        $this->assertEquals('Test', $restaurants[0]->getName());
        $this->assertEquals('http://test.test', $restaurants[0]->getUrl());
        $this->assertEquals(2, $restaurants[1]->getId());
        $this->assertEquals('Test2', $restaurants[1]->getName());
        $this->assertEquals('http://test.test', $restaurants[1]->getUrl());
    }
}