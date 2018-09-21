<?php

namespace App\DailyMenu\Dao;

use App\DailyMenu\Entity\Menu;
use DateTime;
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
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Menu[]
     */
    public function getMenusBetweenDatesByRestaurants(DateTime $startDate, DateTime $endDate)
    {
        $menus = [];
        $statement = $this->pdo->prepare(
            'SELECT m.foods, m.price, m.date, r.id AS restaurant_id, r.name AS restaurant_name
            FROM menus m
            INNER JOIN restaurants r ON m.restaurant_id = r.id
            WHERE date BETWEEN :startDate AND :endDate'
        );
        $statement->execute([
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ]);
        while($menuData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $startDate = $menuData['date'];
            $foods = explode("\n", $menuData['foods']);
            $price = $menuData['price'];
            $menu = new Menu($menuData['restaurant_id'], $foods, $price, new DateTime($startDate));
            $menus[$menuData['restaurant_name']][] = $menu;
        }
        return $menus;
    }


}