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
        $menu = new Menu('Meal soup');
        $this->assertEquals('Meal soup', $menu->getAppetizer());
    }
}