<?php

namespace Test\App\Entity;

use App\Entity\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    /**
     * @test
     */
    public function getAppetizer_ReturnsAppetizer()
    {
        $menu = new Menu('Meal soup', '');
        $this->assertEquals('Meal soup', $menu->getAppetizer());
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
    public function getDrink_ReturnsDrink()
    {
        $menu = new Menu('', '', 'Cola');
        $this->assertEquals('Cola', $menu->getDrink());
    }

    /**
     * @test
     */
    public function getDessert_ReturnsDessert()
    {
        $menu = new Menu('', '', '', 'Cheese cake');
        $this->assertEquals('Cheese cake', $menu->getDessert());
    }
}