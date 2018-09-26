<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\CrawlerException;
use App\DailyMenu\Crawler\VendiakCrawler;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class VendiakCrawlerTest extends DailyMenuSlimTestCase
{

    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler(VendiakCrawler::class, 'vendiak_daily_menu_18_09_24.html')
            ->getDailyMenu(new DateTime('2018-09-24'));

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
    public function getDailyMenu_GivenTomorrowDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler(VendiakCrawler::class, 'vendiak_daily_menu_18_09_24.html')
            ->getDailyMenu(new DateTime('2018-09-25'));

        $this->assertEquals([
            'Házi tea',
            'Paradicsomleves betűtésztával',
            'Cigánypecsenye hasábburgonyával'
        ], $menu->getFoods());
        $this->assertEquals(1590, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenInvalidDateTimeParameter_ThrowsException()
    {
        $this->expectException(CrawlerException::class);
        $this->createCrawler(VendiakCrawler::class, 'vendiak_daily_menu_17_04_22.html')
            ->getDailyMenu(new DateTime('2017-04-22'));
    }

}