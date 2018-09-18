<?php

namespace Test\TodoApp\Integration;

use Test\App\DailyMenuTestCase;

class DailyMenusActionTest extends DailyMenuTestCase
{

    /**
     * @test
     */
    public function callsMenusPage_Returns200WithContents()
    {
        $this->truncateTable('restaurants');
        $this->truncateTable('menus');
        $this->getPDO()->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test Restaurant", "")'
        );
        $this->getPDO()->query(
            'INSERT INTO menus (restaurant_id, foods, price, date) VALUES (1, "test food", 1000, "2018-09-17 10:00:00")'
        );
        $response = $this->runApp('GET', '/menus');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test Restaurant', (string)$response->getBody());
        $this->assertContains('test food', (string)$response->getBody());
        $this->assertContains('1000', (string)$response->getBody());
        $this->assertContains('2018-09-17 10:00:00', (string)$response->getBody());
    }

}