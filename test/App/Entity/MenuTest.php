<?php

namespace Test\App\Entity;

use App\Entity\Menu;
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
}