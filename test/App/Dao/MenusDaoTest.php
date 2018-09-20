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
        $menu = new Menu(1, ['Test foods', 'Test foods 2'], 1000, new \DateTime('2018-09-17'));
        $saved = $this->dao->save($menu);
        $menuData = $this->pdo->query('SELECT * FROM menus WHERE id = 1')->fetch(\PDO::FETCH_ASSOC);
        $this->assertTrue($saved);
        $this->assertEquals(1, $menuData['id']);
        $this->assertEquals(1, $menuData['restaurant_id']);
        $this->assertEquals('Test foods' . "\n" . 'Test foods 2', $menuData['foods']);
        $this->assertEquals('1000', $menuData['price']);
        $this->assertEquals('2018-09-17', $menuData['date']);
    }

    /**
     * @test
     */
    public function getMenusByRestaurants_WithMenus_ReturnsFilteredRecordsFromDatabase()
    {
        $this->pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test", "http://test.test"), ("Test2", "http://test.test")'
        );
        $menu = new Menu(1, ['A Food', 'A Food 2'], 1000, new \DateTime('2018-09-17'));
        $this->dao->save($menu);
        $menu = new Menu(2, ['B Food', 'B Food 2'], 2000, new \DateTime('2019-09-17'));
        $this->dao->save($menu);
        $menu = new Menu(1, ['C Food', 'C Food 2'], 3000, new \DateTime('2019-09-17'));
        $this->dao->save($menu);
        $menus = $this->dao->getMenusByRestaurants(new \DateTime('2019-09-17'));
        $this->assertCount(2, $menus);
        $this->assertEquals(1, $menus['Test']->getRestaurantId());
        $this->assertEquals(['C Food', 'C Food 2'], $menus['Test']->getFoods());
        $this->assertEquals(3000, $menus['Test']->getPrice());
        $this->assertEquals(new \DateTime('2019-09-17'), $menus['Test']->getDate());
        $this->assertEquals(2, $menus['Test2']->getRestaurantId());
        $this->assertEquals(['B Food', 'B Food 2'], $menus['Test2']->getFoods());
        $this->assertEquals(2000, $menus['Test2']->getPrice());
        $this->assertEquals(new \DateTime('2019-09-17'), $menus['Test2']->getDate());
    }
}