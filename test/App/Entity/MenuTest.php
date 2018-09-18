<?php

namespace Test\App\Entity;

use App\DailyMenu\Entity\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{

    /**
     * @test
     */
    public function getDate_ReturnsDate()
    {
        $date = new \DateTime();
        $menu = new Menu($date, []);
        $this->assertEquals($date, $menu->getDate());
    }

    /**
     * @test
     */
    public function getDateInTimestamp_ReturnsDateInTimestamp()
    {
        $date = new \DateTime('2018-09-18 10:00:00');
        $menu = new Menu($date, []);
        $this->assertEquals('2018-09-18 10:00:00', $menu->getDateInTimestamp());
    }

    /**
     * @test
     */
    public function getFoods_ReturnsFoods()
    {
        $menu = new Menu(new \DateTime(), ['Hamburger', 'Cheese cake']);
        $this->assertEquals(['Hamburger', 'Cheese cake'], $menu->getFoods());
    }

    /**
     * @test
     */
    public function getPrice_ReturnsPrice()
    {
        $menu = new Menu(new \DateTime(), [], 1000);
        $this->assertEquals(1000, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getRestaurantId_ReturnsRestaurantId()
    {
        $menu = new Menu(new \DateTime(), []);
        $this->assertEquals(1, $menu->withRestaurantId(1)->getRestaurantId());
    }
}