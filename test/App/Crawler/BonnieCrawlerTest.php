<?php

namespace Test\App\Crawler;

use App\DailyMenu\Crawler\BonnieCrawler;
use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;
use Test\App\DailyMenuTestCase;

class BonnieCrawlerTest extends DailyMenuTestCase
{
    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createBonnieCrawler()->getDailyMenu(new DateTime('2018-09-17'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals(['Zöldbableves', 'Rántott csirkemell salátával'], $menu->getFoods());
        $this->assertEquals(1350, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameterWithHtmlTags_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createBonnieCrawler('bonnie_daily_menu_18_09_17-21_xss.html')->getDailyMenu(new DateTime('2018-09-17'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals(['Zöldbableves', 'Rántott csirkemell salátával'], $menu->getFoods());
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
        $file = __DIR__ . '/assets/' . $assetFile;
        $clientMock = $this->createClientMock($file);
        return new BonnieCrawler($clientMock, new Crawler(), new Restaurant('Bonnie', '', 1));
    }


}