<?php

namespace Test\DailyMenu\Dao;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Dao\MenusDao;
use Test\DailyMenu\DailyMenuSlimTestCase;

class MenusDaoTest extends DailyMenuSlimTestCase
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
    public function getMenusBetweenDatesByRestaurants_WithMenus_ReturnsFilteredRecordsFromDatabase()
    {
        $this->pdo->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test", "http://test.test"), ("Test2", "http://test.test")'
        );

        $menus = [
            new Menu(1, ['A Food', 'A Food 2'], 1000, new \DateTime('2018-09-16')),
            new Menu(2, ['B Food', 'B Food 2'], 2000, new \DateTime('2019-09-17')),
            new Menu(1, ['C Food', 'C Food 2'], 3000, new \DateTime('2019-09-17')),
            new Menu(1, ['D Food', 'D Food 2'], 4000, new \DateTime('2019-09-18'))
        ];

        foreach($menus as $menu) {
            $this->dao->save($menu);
        }

        $menus = $this->dao->getMenusBetweenDatesByRestaurants(new \DateTime('2019-09-17'), new \DateTime('2019-09-18'));
        $this->assertCount(2, $menus);
        $this->assertEquals(2, $menus['Test2'][0]->getRestaurantId());
        $this->assertEquals(1, $menus['Test'][0]->getRestaurantId());
        $this->assertEquals(1, $menus['Test'][1]->getRestaurantId());
        $this->assertEquals(['D Food', 'D Food 2'], $menus['Test'][1]->getFoods());
    }
}