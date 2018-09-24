<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Entity\Menu;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class BonnieCrawlerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function getDailyMenu_GivenLeftDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createBonnieCrawler()->getDailyMenu(new DateTime('2018-09-17'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals(['Zöldbableves', 'Rántott csirkemell salátával'], $menu->getFoods());
        $this->assertEquals(1350, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenRightDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createBonnieCrawler()->getDailyMenu(new DateTime('2018-09-24'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals([
            'Májgaluskaleves', 'Roston csirkemell paradicsommal, mozzarellával és párolt rizzsel'
        ], $menu->getFoods());
        $this->assertEquals(1350, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_WithEdgeDateFormatGivenLeftDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createBonnieCrawler('bonnie_daily_menu_18_02_26-03_09.html')->getDailyMenu(new DateTime('2018-02-26'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals([
            'Pedro zöldséglevese', 'Áfonyalekvárral és camemberttel sült csirkemell hasábburgonyával'
        ], $menu->getFoods());
        $this->assertEquals(1350, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenInvalidDateTimeParameter_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->createBonnieCrawler()->getDailyMenu(new DateTime('2018-09-22'));
    }

    private function createBonnieCrawler($assetFile = 'bonnie_daily_menu_18_09_17-21.html') {
        return $this->createCrawler(BonnieCrawler::class, $assetFile);
    }


}