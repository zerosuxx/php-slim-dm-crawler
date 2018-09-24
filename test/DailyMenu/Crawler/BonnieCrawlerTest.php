<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class BonnieCrawlerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function getDailyMenu_GivenLeftDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler()->getDailyMenu(new DateTime('2018-09-17'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals(['Zöldbableves', 'Rántott csirkemell salátával'], $menu->getFoods());
        $this->assertEquals(1350, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenRightDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler()->getDailyMenu(new DateTime('2018-09-24'));
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
        $menu = $this->createCrawler('bonnie_daily_menu_18_02_26-03_09.html')->getDailyMenu(new DateTime('2018-02-26'));
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
        $this->createCrawler()->getDailyMenu(new DateTime('2018-09-22'));
    }

    private function createCrawler($assetFile = 'bonnie_daily_menu_18_09_17-21.html') {
        $file = __DIR__ . '/assets/' . $assetFile;
        $clientMock = $this->createClientMock($file);
        return new BonnieCrawler($clientMock, $this->getService('domCrawler'), new Restaurant('Bonnie', '', 1));
    }


}