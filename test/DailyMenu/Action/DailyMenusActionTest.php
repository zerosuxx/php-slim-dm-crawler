<?php

namespace Test\DailyMenu\Action;

use Test\DailyMenu\DailyMenuSlimTestCase;

class DailyMenusActionTest extends DailyMenuSlimTestCase
{

    protected function setUp()
    {
        $this->truncateTable('restaurants');
        $this->truncateTable('menus');
    }

    /**
     * @test
     */
    public function callsMenusPage_WithoutDate_Returns200WithContents()
    {
        $response = $this->runApp('GET', '/');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function callsMenusPage_WithStartDate_Returns200WithContents()
    {

        $this->getPDO()->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test Restaurant", "")'
        );
        $this->getPDO()->query(
            'INSERT INTO menus (restaurant_id, foods, price, date) VALUES (1, "test food", 1000, "2018-09-17")'
        );
        $response = $this->runApp('GET', '/?start_date=2018-09-17');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test Restaurant', (string)$response->getBody());
        $this->assertContains('test food', (string)$response->getBody());
        $this->assertContains('1000', (string)$response->getBody());
    }

    /**
     * @test
     */
    public function callsMenusPage_WithStartDateAndEndDate_Returns200WithContents()
    {
        $this->getPDO()->query(
            'INSERT INTO restaurants (name, url) VALUES ("Test Restaurant", ""), ("Test Restaurant 2", "")'
        );
        $this->getPDO()->query(
            'INSERT INTO menus (restaurant_id, foods, price, date) VALUES (1, "test food", 1000, "2018-09-17"), (2, "test food 2", 2000, "2018-09-18")'
        );
        $response = $this->runApp('GET', '/?start_date=2018-09-17&end_date=2018-09-18');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test Restaurant', (string)$response->getBody());
        $this->assertContains('test food', (string)$response->getBody());
        $this->assertContains('1000', (string)$response->getBody());
        $this->assertContains('Test Restaurant 2', (string)$response->getBody());
        $this->assertContains('test food 2', (string)$response->getBody());
        $this->assertContains('2000', (string)$response->getBody());
    }

}