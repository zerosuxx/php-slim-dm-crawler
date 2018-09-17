<?php

namespace Test\App\Entity;

use App\DailyMenu\Entity\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{

    /**
     * @test
     */
    public function getFoods_ReturnsFoods()
    {
        $menu = new Menu('Hamburger, Cheese cake');
        $this->assertEquals('Hamburger, Cheese cake', $menu->getFoods());
    }

    /**
     * @test
     */
    public function getPrice_ReturnsPrice()
    {
        $menu = new Menu('', 1000);
        $this->assertEquals(1000, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getRestaurantId_ReturnsRestaurantId()
    {
        $menu = new Menu('', null, 1);
        $this->assertEquals(1, $menu->getRestaurantId());
    }
}