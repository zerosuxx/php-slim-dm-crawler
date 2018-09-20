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
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $name
     * @return Restaurant
     */
    public function getRestaurant(string $name) {
        $statement = $this->pdo->prepare('SELECT url, id FROM restaurants WHERE name = :name');
        $statement->execute(['name' => $name]);

        $restaurantData = $statement->fetch(PDO::FETCH_ASSOC);
        return $this->createRestaurant($name, $restaurantData['url'], $restaurantData['id']);
    }

    /**
     * @param \DateTime $dateTime
     * @return Restaurant[]|array
     */
    public function getDailyRestaurants(\DateTime $dateTime)
    {
        $statement = $this->pdo->prepare(
            'SELECT r.id, r.name, r.url
            FROM restaurants r
            LEFT JOIN menus m ON r.id = m.restaurant_id AND m.date >= :date
            WHERE m.date IS NULL
            ORDER BY r.id'
        );
        $statement->execute([
            'date' => $dateTime->format('Y-m-d')
        ]);
        return $this->createRestaurants($statement);
    }

    /**
     * @param string $name
     * @param string $url
     * @param int $id
     * @return Restaurant
     */
    private function createRestaurant(string $name, string $url, int $id)
    {
        return new Restaurant($name, $url, $id);
    }

    /**
     * @param \PDOStatement $statement
     * @return Restaurant[]
     */
    private function createRestaurants(\PDOStatement $statement): array
    {
        $restaurants = [];
        while ($restaurantData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $restaurants[] = $this->createRestaurant(
                $restaurantData['name'],
                $restaurantData['url'],
                $restaurantData['id']
            );
        }
        return $restaurants;
    }

}