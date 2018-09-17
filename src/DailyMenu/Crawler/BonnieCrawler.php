<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use DateTime;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BonnieCrawler
 * @package App\DailyMenu\Crawler
 */
class BonnieCrawler
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Crawler
     */
    private $domCrawler;

    /**
     * @var string
     */
    private $url;

    /**
     * @param Client $client
     * @param Crawler $domCrawler
     * @param string $url [optional]
     */
    public function __construct(Client $client, Crawler $domCrawler, $url = 'http://bonnierestro.hu/hu/napimenu/')
    {
        $this->client = $client;
        $this->domCrawler = $domCrawler;
        $this->url = $url;
    }

    /**
     * @param DateTime $date
     * @return Menu
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDailyMenu(DateTime $date): Menu
    {
        $dayOfWeek = $date->format('N');
        $response = $this->client->request('GET', $this->url);
        $this->domCrawler->addHtmlContent((string)$response->getBody());

        $dailyMenu = $this->domCrawler->filter('#left_column table')->eq($dayOfWeek-1);
        if(!$dailyMenu->count()) {
            throw new InvalidArgumentException('Daily menu not found for this date');
        }

        $soup = trim($dailyMenu->filter('tr:nth-child(2) td:nth-child(3)')->text());
        $mainCourse = trim($dailyMenu->filter('tr:nth-child(3) td:nth-child(3)')->text());

        $title = $this->domCrawler->filter('h2')->text();
        preg_match('/([0-9]+)/', $title, $titleMatches);
        $price = (int)$titleMatches[1];

        return new Menu($soup . "\n" . $mainCourse, $price);
    }


}