<?php

namespace Test\App\Dao;

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
        $dao = new \App\DailyMenu\Dao\RestaurantsDao($pdo);

        $restaurant = $dao->getRestaurant('Bonnie');
        $this->assertEquals(1, $restaurant['id']);
        $this->assertEquals('Bonnie', $restaurant['name']);
        $this->assertEquals('http://bonnierestro.hu/hu/napimenu/', $restaurant['url']);
    }
}