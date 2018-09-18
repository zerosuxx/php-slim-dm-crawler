<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Dao\RestaurantsDao;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CrawlerFactory
 * @package App\DailyMenu\Crawler
 */
class CrawlerFactory
{
    /**
     * @var RestaurantsDao
     */
    private $dao;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @param RestaurantsDao $dao
     * @param Client $client
     * @param Crawler $crawler
     */
    public function __construct(RestaurantsDao $dao, Client $client, Crawler $crawler)
    {
        $this->dao = $dao;
        $this->client = $client;
        $this->crawler = $crawler;
    }

    /**
     * @param $name
     * @return AbstractCrawler
     */
    public function createCrawlerFromName($name): AbstractCrawler
    {
        $restaurant = $this->dao->getRestaurant($name);
        return $this->buildCrawler(
            $restaurant->getCrawlerClass(),
            $this->client,
            $this->crawler,
            $restaurant->getUrl()
        );
    }

    private function buildCrawler($class, Client $client, Crawler $crawler, string $url): AbstractCrawler {
        return new $class($client, $crawler, $url);
    }


}