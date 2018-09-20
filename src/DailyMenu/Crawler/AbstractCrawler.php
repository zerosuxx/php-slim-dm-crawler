<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
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
     * @var Restaurant
     */
    private $restaurant;

    /**
     * @param Client $client
     * @param Crawler $domCrawler
     * @param Restaurant $restaurant
     */
    public function __construct(Client $client, Crawler $domCrawler, Restaurant $restaurant)
    {
        $this->client = $client;
        $this->domCrawler = $domCrawler;
        $this->restaurant = $restaurant;
    }

    /**
     * @param Restaurant $restaurant
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return Menu
     */
    abstract protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu;

    /**
     * @param DateTime|null $date [optional]
     * @return Menu
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDailyMenu(DateTime $date = null): Menu
    {
        $response = $this->client->request('GET', $this->restaurant->getUrl());
        $this->domCrawler->addHtmlContent((string)$response->getBody());
        return $this->createMenu($this->restaurant, $date, $this->domCrawler);
    }
}