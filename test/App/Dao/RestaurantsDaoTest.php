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
            'INSERT INTO restaurants (name, url) VALUES ("Bonnie", "http://bonnierestro.hu/hu/napimenu/")'
        );

    }

    /**
     * @test
     */
    public function getRestaurant_ReturnsRestaurant()
    {
        $dao = new RestaurantsDao($this->pdo, ['Bonnie' => BonnieCrawler::class]);

        $restaurant = $dao->getRestaurant('Bonnie');
        $this->assertInstanceOf(Restaurant::class, $restaurant);
        $this->assertEquals(1, $restaurant->getId());
        $this->assertEquals('Bonnie', $restaurant->getName());
        $this->assertEquals('http://bonnierestro.hu/hu/napimenu/', $restaurant->getUrl());
        $this->assertEquals(BonnieCrawler::class, $restaurant->getCrawlerClass());
    }

    /**
     * @test
     */
    public function getRestaurant_WithoutCrawlerAliases_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $dao = new RestaurantsDao($this->pdo, []);

        $dao->getRestaurant('Bonnie');
    }

    /**
     * @test
     */
    public function getRestaurants_ReturnsRestaurants()
    {
        $dao = new RestaurantsDao($this->pdo, ['Bonnie' => BonnieCrawler::class]);

        $restaurants = $dao->getRestaurants();
        $this->assertCount(1, $restaurants);
        $this->assertEquals(1, $restaurants[0]->getId());
        $this->assertEquals('Bonnie', $restaurants[0]->getName());
        $this->assertEquals('http://bonnierestro.hu/hu/napimenu/', $restaurants[0]->getUrl());
    }
}