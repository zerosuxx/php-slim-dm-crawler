<?php

namespace Test\DailyMenu\ntity;

use App\DailyMenu\Entity\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{

    /**
     * @test
     */
    public function getRestaurantId_ReturnsRestaurantId()
    {
        $menu = new Menu(1, []);
        $this->assertEquals(1, $menu->getRestaurantId());
    }

    /**
     * @test
     */
    public function getFoods_ReturnsFoods()
    {
        $menu = new Menu(0, ['Hamburger', 'Cheese cake']);
        $this->assertEquals(['Hamburger', 'Cheese cake'], $menu->getFoods());
    }

    /**
     * @test
     */
    public function getPrice_ReturnsPrice()
    {
        $menu = new Menu(0, [], 1000);
        $this->assertEquals(1000, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDate_WithoutGivenDate_ReturnsNewDate()
    {
        $menu = new Menu(0, []);
        $this->assertNotNull($menu->getDate());
    }

    /**
     * @test
     */
    public function getDate_ReturnsDate()
    {
        $date = new \DateTime();
        $menu = new Menu(0, [], 0, $date);
        $this->assertEquals($date, $menu->getDate());
    }

    /**
     * @test
     */
    public function getDateString_ReturnsDateInString()
    {
        $date = new \DateTime('2018-09-18');
        $menu = new Menu(0, [], 0, $date);
        $this->assertEquals('2018-09-18', $menu->getDateString());
    }
}