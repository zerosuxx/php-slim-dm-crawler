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
        $crawlerAlias = $this->getCrawlerAlias($name);
        $statement = $this->pdo->prepare('SELECT url, id FROM restaurants WHERE name = :name');
        $statement->execute(['name' => $name]);
        $restaurantData = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->createRestaurant($name, $restaurantData['url'], $restaurantData['id'], $crawlerAlias);
    }

    private function createRestaurant($name, $url, $id, $crawlerClass)
    {
        $restaurant = new Restaurant($name, $url, $id);
        return $restaurant->withCrawlerClass($crawlerClass);
    }

    /**
     * @return Restaurant[]
     */
    public function getRestaurants()
    {
        $restaurants = [];
        $statement = $this->pdo->prepare('SELECT name, url, id FROM restaurants');
        $statement->execute();
        while($restaurantData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $name = $restaurantData['name'];
            $restaurants[] = $this->createRestaurant(
                $name,
                $restaurantData['url'],
                $restaurantData['id'],
                $this->getCrawlerAlias($name)
            );
        }
        return $restaurants;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function getCrawlerAlias(string $name) {
        if(!isset($this->crawlerAliases[$name])) {
            throw new \InvalidArgumentException('Crawler alias not found');
        }
        return $this->crawlerAliases[$name];
    }
}