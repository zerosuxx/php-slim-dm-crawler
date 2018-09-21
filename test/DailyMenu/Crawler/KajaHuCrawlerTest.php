<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\KajaHuCrawler;
use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class KajaHuCrawlerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function getDailyMenu_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler()->getDailyMenu(new DateTime('2018-09-20'));
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
        $this->expectException(\InvalidArgumentException::class);
        $this->createCrawler()->getDailyMenu(new DateTime('2018-09-25'));
    }


    private function createCrawler($assetFile = 'kajahu_daily_menu_18_09_20.json') {
        $file = __DIR__ . '/assets/' . $assetFile;
        $clientMock = $this->createClientMock($file);
        return new KajaHuCrawler($clientMock, $this->getService('domCrawler'), new Restaurant('Kajahu', '', 1));
    }


}