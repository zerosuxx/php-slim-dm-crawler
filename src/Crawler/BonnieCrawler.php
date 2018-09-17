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
        $appetizer = $dailyMenu->filter('tr:nth-child(2) td:nth-child(3)');
        $mainCourse= $dailyMenu->filter('tr:nth-child(3) td:nth-child(3)');

        return new Menu(trim($appetizer->text()), trim($mainCourse->text()));

    }


}