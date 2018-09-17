<?php

namespace Test\App\Entity;

use App\Entity\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    /**
     * @test
     */
    public function getSoup_ReturnsSoup()
    {
        $menu = new Menu('Meal soup', '');
        $this->assertEquals('Meal soup', $menu->getSoup());
    }

    /**
     * @test
     */
    public function getMainCourse_ReturnsMainCourse()
    {
        $menu = new Menu('', 'Hamburger');
        $this->assertEquals('Hamburger', $menu->getMainCourse());
    }

    /**
     * @test
     */
    public function getPrice_ReturnsPrice()
    {
        $menu = new Menu('', '', 1000);
        $this->assertEquals(1000, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDessert_ReturnsDessert()
    {
        $menu = new Menu('', '');
        $this->assertEquals('Cheese cake', $menu->withDessert('Cheese cake')->getDessert());
    }
}