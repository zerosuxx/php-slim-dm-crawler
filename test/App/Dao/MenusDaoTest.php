<?php

namespace Test\App\Dao;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Dao\MenusDao;
use Test\App\DailyMenuTestCase;

class MenusDaoTest extends DailyMenuTestCase
{
    /**
     * @test
     */
    public function saveMenu_GivenMenu_SaveToDatabase()
    {
        $this->truncateTable('menus');
        $pdo = $this->getPDO();
        $dao = new MenusDao($pdo);
        $menu = new Menu(new \DateTime('2018-09-17 10:00:00'), ['Test foods', 'Test foods 2'], 1000);
        $saved = $dao->save($menu->withRestaurantId(1));
        $menuData = $pdo->query('SELECT * FROM menus WHERE id = 1')->fetch(\PDO::FETCH_ASSOC);
        $this->assertTrue($saved);
        $this->assertEquals(1, $menuData['id']);
        $this->assertEquals(1, $menuData['restaurant_id']);
        $this->assertEquals('Test foods' . "\n" . 'Test foods 2', $menuData['foods']);
        $this->assertEquals('1000', $menuData['price']);
        $this->assertEquals('2018-09-17 10:00:00', $menuData['date']);
    }
}