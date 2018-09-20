<?php

namespace Test\App\Dao;

use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Entity\Menu;
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
        $this->truncateTable('menus');
        $this->pdo->query(
            'INSERT INTO restaurants (name, url)
            VALUES ("Test", "http://test.test"), ("Test2", "http://test.test"), ("Test3", "http://test.test")'
        );
    }

    /**
     * @test
     */
    public function getRestaurant_ReturnsRestaurant()
    {
        $dao = new RestaurantsDao($this->pdo);

        $restaurant = $dao->getRestaurant('Test');
        $this->assertInstanceOf(Restaurant::class, $restaurant);
        $this->assertEquals(1, $restaurant->getId());
        $this->assertEquals('Test', $restaurant->getName());
        $this->assertEquals('http://test.test', $restaurant->getUrl());
    }

    /**
     * @test
     */
    public function getDailyRestaurants_WithMenus_ReturnsFilteredDailyRestaurants()
    {
        $menusDao = new MenusDao($this->pdo);
        $menusDao->save(new Menu(3, [], 0, new \DateTime('2018-09-19')));
        $menusDao->save(new Menu(1, [], 0, new \DateTime('2018-09-20')));
        $menusDao->save(new Menu(2, [], 0, new \DateTime('2018-09-20')));
        $menusDao->save(new Menu(3, [], 0, new \DateTime('2018-09-21')));
        $dao = new RestaurantsDao($this->pdo);

        $restaurants = $dao->getDailyRestaurants(new \DateTime('2018-09-21'));
        $this->assertCount(2, $restaurants);
        $this->assertEquals(1, $restaurants[0]->getId());
        $this->assertEquals(2, $restaurants[1]->getId());
    }

    /**
     * @test
     */
    public function getDailyRestaurants_WithoutMenus_ReturnsAllDailyRestaurants()
    {
        $dao = new RestaurantsDao($this->pdo);

        $restaurants = $dao->getDailyRestaurants(new \DateTime());
        $this->assertCount(3, $restaurants);
    }
}