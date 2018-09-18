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
            'date' => $menu->getDateInTimestamp()
        ]);
    }

    /**
     * @return Menu[]
     */
    public function getMenus()
    {
        $menus = [];
        $statement = $this->pdo->prepare(
            'SELECT m.foods, m.price, m.date, r.name FROM menus m LEFT JOIN restaurants r ON m.restaurant_id = r.id'
        );
        $statement->execute();
        while($menuData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $date = $menuData['date'];
            $foods = explode("\n", $menuData['foods']);
            $price = $menuData['price'];
            $menu = new Menu(new \DateTime($date), $foods, $price);
            $menus[] = $menu->withRestaurantName($menuData['name']);
        }
        return $menus;
    }


}