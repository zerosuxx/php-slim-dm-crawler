<?php

namespace Test\DailyMenu\Crawler;

use App\DailyMenu\Crawler\NikaCrawler;
use DateTime;
use Test\DailyMenu\DailyMenuSlimTestCase;

class NikaCrawlerTest extends DailyMenuSlimTestCase
{
    /**
     * @var NikaCrawler
     */
    private $crawler;

    protected function setUp()
    {
        $this->crawler = $this->createCrawler(
            NikaCrawler::class,
            'nika_daily_menu_18_09_24-25.html'
        );
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->crawler->getDailyMenu(new \DateTime('2018-09-24'));

        $this->assertEquals([
            'Bableves',
            'Lasagne',
            'Mézes mustáros csirkemell jázmin rizzsel',
            'Desszert: Oreo kehely (menühöz választható - 300Ft)'
        ], $menu->getFoods());
        $this->assertEquals(1290, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenTomorrowDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $menu = $this->crawler->getDailyMenu(new \DateTime('2018-09-25'));

        $this->assertEquals([
            'tervezés alatt',
            'Rántott csirkemell petrezselymes burgonyával',
            'Sárgaborsófőzelék sült debrecenivel',
            'Desszert: ? (menühöz választható - 300Ft)'
        ], $menu->getFoods());
        $this->assertEquals(1290, $menu->getPrice());
    }

    /**
     * @test
     */
    public function getDailyMenu_GivenInvalidDateTimeParameter_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->crawler->getDailyMenu(new DateTime('2018-09-23'));
    }

}