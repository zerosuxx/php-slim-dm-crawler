<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use DateTime;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractCrawler
 * @package App\DailyMenu\Crawler
 */
abstract class AbstractCrawler
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
     * @param string $url
     */
    public function __construct(Client $client, Crawler $domCrawler, string $url)
    {
        $this->client = $client;
        $this->domCrawler = $domCrawler;
        $this->url = $url;
    }

    /**
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return Menu
     */
    abstract protected function createMenu(DateTime $date, Crawler $domCrawler): Menu;

    /**
     * @param DateTime $date
     * @return Menu
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDailyMenu(DateTime $date): Menu
    {
        $response = $this->client->request('GET', $this->url);
        $this->domCrawler->addHtmlContent((string)$response->getBody());
        return $this->createMenu($date, $this->domCrawler);
    }
}