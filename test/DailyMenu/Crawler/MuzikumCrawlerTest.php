<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\CrawlerException;
use App\DailyMenu\Crawler\MuzikumCrawler;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class MuzikumCrawlerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler(
            MuzikumCrawler::class,
            'muzikum_daily_menu_18_09_24-28.html'
        )->getDailyMenu(new \DateTime('2018-09-24'));

        $this->assertEquals([
            'Francia hagymaleves diós veknivel',
            'Csirkemell sajttal, sonkával sütve, petrezselymes burgonyával',
        ], $menu->getFoods());
        $this->assertEquals(1390, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenTomorrowDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->createCrawler(
            MuzikumCrawler::class,
            'muzikum_daily_menu_18_09_24-28.html'
        )->getDailyMenu(new \DateTime('2018-09-25'));

        $this->assertEquals([
            'Burgonyás kelleves kéksajtos tejföllel',
            'Háromborsos sertésborda tejfölös jalapeno-val, jázmin rizzsel',
        ], $menu->getFoods());
        $this->assertEquals(1390, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenInvalidDateTimeParameter_ThrowsException()
    {
        $this->expectException(CrawlerException::class);
        $this->createCrawler(
            MuzikumCrawler::class,
            'muzikum_daily_menu_18_08_21-24.html'
        )->getDailyMenu(new DateTime('2018-08-20'));
    }

}