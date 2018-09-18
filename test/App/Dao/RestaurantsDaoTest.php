<?php

namespace Test\App\Dao;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Entity\Restaurant;
use Test\App\DailyMenuTestCase;

class RestaurantsDaoTest extends DailyMenuTestCase
{
    /**
     * @test
     */
    public function getRestaurant_ReturnsRestaurant()
    {
        $pdo = $this->getPDO();
        $pdo->query('SET foreign_key_checks=0');
        $this->truncateTable('restaurants');
        $pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Bonnie", "http://bonnierestro.hu/hu/napimenu/")'
        );
        $dao = new RestaurantsDao($pdo, ['Bonnie' => BonnieCrawler::class]);

        $restaurant = $dao->getRestaurant('Bonnie');
        $this->assertInstanceOf(Restaurant::class, $restaurant);
        $this->assertEquals('Bonnie', $restaurant->getName());
        $this->assertEquals('http://bonnierestro.hu/hu/napimenu/', $restaurant->getUrl());
        $this->assertEquals(BonnieCrawler::class, $restaurant->getCrawlerClass());
    }
}