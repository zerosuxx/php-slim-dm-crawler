<?php

namespace App\Crawler;

use App\Entity\Menu;
use DateTime;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BonnieCrawler
 * @package App\Crawler
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
     * @param Client $client
     * @param Crawler $domCrawler
     */
    public function __construct(Client $client, Crawler $domCrawler)
    {
        $this->client = $client;
        $this->domCrawler = $domCrawler;
    }

    public function getDailyMenu(DateTime $date): Menu
    {
        $dayNum = $date->format('N');
        $response = $this->client->request('GET', 'http://bonnierestro.hu/hu/napimenu/');
        $this->domCrawler->addHtmlContent((string)$response->getBody());

        $dailyMenu = $this->domCrawler->filter('#left_column table')->eq($dayNum-1);
        if(!$dailyMenu->count()) {
            throw new \InvalidArgumentException('Daily menu not found for this date');
        }

        $appetizer = trim($dailyMenu->filter('tr:nth-child(2) td:nth-child(3)')->text());
        $mainCourse= trim($dailyMenu->filter('tr:nth-child(3) td:nth-child(3)')->text());

        $title = $this->domCrawler->filter('h2')->text();
        preg_match('/([0-9]+)/', $title, $titleMatches);
        $price = (int)$titleMatches[1];

        return new Menu($appetizer, $mainCourse, $price);
    }


}