<?php

namespace App\DailyMenu\Dao;

use App\DailyMenu\Entity\Menu;
use PDO;

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
            'date' => $menu->getDateString()
        ]);
    }

    /**
     * @return Menu[]
     */
    public function getMenusByRestaurants(\DateTime $date)
    {
        $menus = [];
        $statement = $this->pdo->prepare(
            'SELECT m.foods, m.price, m.date, r.id AS restaurant_id, r.name AS restaurant_name
            FROM menus m
            INNER JOIN restaurants r ON m.restaurant_id = r.id
            WHERE date = :date
            GROUP BY r.id'
        );
        $statement->execute(['date' => $date->format('Y-m-d')]);
        while($menuData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $date = $menuData['date'];
            $foods = explode("\n", $menuData['foods']);
            $price = $menuData['price'];
            $menu = new Menu($menuData['restaurant_id'], $foods, $price, new \DateTime($date));
            $menus[$menuData['restaurant_name']] = $menu;
        }
        return $menus;
    }


}