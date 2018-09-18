<?php

namespace App\DailyMenu\Dao;

use App\DailyMenu\Entity\Menu;

/**
 * Class MenusDao
 * @package App\Dao
 */
class MenusDao
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Menu $menu)
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO menus (restaurant_id, foods, price, date) VALUES (:restaurant_id, :foods, :price, :date)'
        );
        return $statement->execute([
            'restaurant_id' => $menu->getRestaurantId(),
            'foods' => implode("\n", $menu->getFoods()),
            'price' => $menu->getPrice(),
            'date' => $menu->getDateInTimestamp()
        ]);
    }


}