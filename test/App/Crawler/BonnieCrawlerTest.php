<?php

namespace Test\App\Crawler;

use App\Crawler\BonnieCrawler;
use App\Entity\Menu;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BonnieCrawlerTest
 * @package Test\App\Crawler
 */
class BonnieCrawlerTest extends TestCase
{
    /**
     * @test
     */
    public function getDailyMenu_GivenTodayDateTimeParameter_ReturnsCurrentDailyMenu()
    {
        $file = __DIR__ . '/../assets/bonnie_daily_menu_18_09_17-21.html';
        $crawler = new BonnieCrawler($this->createClientMock(file_get_contents($file)), new Crawler());
        $menu = $crawler->getDailyMenu(new \DateTime('2018-09-17'));

        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals('Zöldbableves', $menu->getAppetizer());
        $this->assertEquals('Rántott csirkemell salátával', $menu->getMainCourse());
    }

    private function createClientMock(string $contents) {
        $bodyMock = $this->createMock(StreamInterface::class);
        $bodyMock
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($contents);

        $responseMock = $this->createMock(ResponseInterface::class);

        $responseMock
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($bodyMock);


        $clientMock = $this->createMock(Client::class);
        $clientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);
        return $clientMock;
    }
}