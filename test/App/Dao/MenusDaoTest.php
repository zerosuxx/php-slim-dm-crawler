<?php

namespace Test\App\Dao;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Dao\MenusDao;
use Test\App\DailyMenuTestCase;

class MenusDaoTest extends DailyMenuTestCase
{
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var MenusDao
     */
    private $dao;

    protected function setUp()
    {
        $this->truncateTable('menus');
        $this->truncateTable('restaurants');
        $this->pdo = $this->getPDO();
        $this->dao = new MenusDao($this->pdo);
    }

    /**
     * @test
     */
    public function saveMenu_GivenMenu_SaveToDatabase()
    {
        $menu = new Menu(new \DateTime('2018-09-17 10:00:00'), ['Test foods', 'Test foods 2'], 1000);
        $saved = $this->dao->save($menu->withRestaurantId(1));
        $menuData = $this->pdo->query('SELECT * FROM menus WHERE id = 1')->fetch(\PDO::FETCH_ASSOC);
        $this->assertTrue($saved);
        $this->assertEquals(1, $menuData['id']);
        $this->assertEquals(1, $menuData['restaurant_id']);
        $this->assertEquals('Test foods' . "\n" . 'Test foods 2', $menuData['foods']);
        $this->assertEquals('1000', $menuData['price']);
        $this->assertEquals('2018-09-17 10:00:00', $menuData['date']);
    }

    /**
     * @test
     */
    public function getMenus_WithRestaurantNames_ReturnsRecordsFromDatabase()
    {
        $this->pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test", "http://test.test")'
        );
        $this->pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test2", "http://test.test")'
        );
        $menu = new Menu(new \DateTime('2018-09-17 10:00:00'), ['A Food', 'A Food 2'], 1000);
        $this->dao->save($menu->withRestaurantId(1));
        $menu = new Menu(new \DateTime('2019-09-17 10:00:00'), ['B Food', 'B Food 2'], 2000);
        $this->dao->save($menu->withRestaurantId(2));
        $menus = $this->dao->getMenus();
        $this->assertCount(2, $menus);
        $this->assertEquals(new \DateTime('2018-09-17 10:00:00'), $menus[0]->getDate());
        $this->assertEquals(['A Food', 'A Food 2'], $menus[0]->getFoods());
        $this->assertEquals(1000, $menus[0]->getPrice());
        $this->assertEquals('Test', $menus[0]->getRestaurantName());
        $this->assertEquals(new \DateTime('2019-09-17 10:00:00'), $menus[1]->getDate());
        $this->assertEquals(['B Food', 'B Food 2'], $menus[1]->getFoods());
        $this->assertEquals(2000, $menus[1]->getPrice());
        $this->assertEquals('Test2', $menus[1]->getRestaurantName());
    }
}