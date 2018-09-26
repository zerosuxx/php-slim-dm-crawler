<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\CrawlerException;
use App\DailyMenu\Crawler\KajaHuCrawler;
use App\DailyMenu\Entity\Menu;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class KajaHuCrawlerTest extends DailyMenuSlimTestCase
{

    /**
     * @var KajaHuCrawler
     */
    private $crawler;

    protected function setUp()
    {
        $this->crawler = $this->createCrawler(KajaHuCrawler::class, 'kajahu_daily_menu_18_09_20.json');
    }

    /**
     * @test
     */
    public function getDailyMenu_ReturnsCurrentDailyMenu()
    {
        $menu = $this->crawler->getDailyMenu(new DateTime('2018-09-20'));
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals([
            'Francia hagymaleves',
            'Szecsuáni csirke jázmin rizzsel',
            'Csokis keksz vaníliával'], $menu->getFoods());
        $this->assertEquals(1390, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenInvalidDateTimeParameter_ThrowsException()
    {
        $this->expectException(CrawlerException::class);
        $this->crawler->getDailyMenu(new DateTime('2018-09-25'));
    }
}