<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\VendiakCrawler;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class VendiakCrawlerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler()->getDailyMenu(new \DateTime('2018-09-24'));

        $this->assertEquals([
            'Házi tea',
            'Karfiolleves',
            'Csirkepaprikás szarvacskával'
        ], $menu->getFoods());
        $this->assertEquals(1590, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenInvalidDateTimeParameter_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->createCrawler('vendiak_daily_menu_17_04_22.html')->getDailyMenu(new DateTime('2017-04-22'));
    }

    private function createCrawler($assetFile = 'vendiak_daily_menu_18_09_24.html') {
        $file = __DIR__ . '/assets/' . $assetFile;
        $clientMock = $this->createClientMock($file);
        return new VendiakCrawler($clientMock, $this->getService('domCrawler'), new Restaurant('Vendiak', '', 1));
    }

}