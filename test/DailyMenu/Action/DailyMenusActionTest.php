<?php

namespace Test\DailyMenu\Action;

use Test\DailyMenu\DailyMenuSlimTestCase;

class DailyMenusActionTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function callsMenusPage_WithoutDate_Returns200WithContents()
    {
        $response = $this->runApp('GET', '/menus');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string)$response->getBody());
    }

    /**
     * @test
     */
    public function callsMenusPage_WithDate_Returns200WithContents()
    {
        $this->truncateTable('restaurants');
        $this->truncateTable('menus');
        $this->getPDO()->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test Restaurant", "")'
        );
        $this->getPDO()->query(
            'INSERT INTO menus (restaurant_id, foods, price, date) VALUES (1, "test food", 1000, "2018-09-17")'
        );
        $response = $this->runApp('GET', '/menus/2018-09-17');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test Restaurant', (string)$response->getBody());
        $this->assertContains('test food', (string)$response->getBody());
        $this->assertContains('1000', (string)$response->getBody());
    }

}