<?php

namespace App\DailyMenu\Dao;

use App\DailyMenu\Entity\Restaurant;
use PDO;

/**
 * Class RestaurantsDao
 * @package App\DailyMenu\Dao
 */
class RestaurantsDao
{
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var array
     */
    private $crawlerAliases;

    /**
     * @param PDO $pdo
     * @param array $crawlerAliases
     */
    public function __construct(PDO $pdo, array $crawlerAliases)
    {
        $this->pdo = $pdo;
        $this->crawlerAliases = $crawlerAliases;
    }

    /**
     * @param string $name
     * @return Restaurant
     */
    public function getRestaurant(string $name) {
        $statement = $this->pdo->prepare('SELECT url FROM restaurants WHERE name = :name');
        $statement->execute(['name' => $name]);
        $restaurantData = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->createRestaurant($name, $restaurantData['url'], $this->crawlerAliases[$name]);
    }

    private function createRestaurant($name, $url, $crawlerClass)
    {
        $restaurant = new Restaurant($name, $url);
        return $restaurant->withCrawlerClass($crawlerClass);
    }
}