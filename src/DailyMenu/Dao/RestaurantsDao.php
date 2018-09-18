<?php

namespace App\DailyMenu\Dao;

/**
 * Class RestaurantsDao
 * @package App\DailyMenu\Dao
 */
class RestaurantsDao
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRestaurant(string $name) {
        $statement = $this->pdo->prepare('SELECT id, name, url FROM restaurants WHERE name = :name');
        $statement->execute(['name' => $name]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}