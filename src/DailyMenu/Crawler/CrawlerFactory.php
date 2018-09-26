<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Dao\RestaurantsDao;
use App\DailyMenu\Entity\Restaurant;
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
     * @var array
     */
    private $classMap = [
        'Bonnie' => BonnieCrawler::class,
        'KajaHu' => KajaHuCrawler::class,
        'Nika' => NikaCrawler::class,
        'Véndiák' => VendiakCrawler::class,
        'Muzikum' => MuzikumCrawler::class
    ];

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
     * @param string $name
     * @return AbstractCrawler
     */
    public function getCrawlerFromRestaurantName(string $name): AbstractCrawler
    {
        $crawlerClass = $this->getCrawlerClass($name);
        $restaurant = $this->dao->getRestaurant($name);
        return $this->buildCrawler(
            $crawlerClass,
            $this->client,
            $this->crawler,
            $restaurant
        );
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function getCrawlerClass(string $name) {
        if(!isset($this->classMap[$name])) {
            throw new \InvalidArgumentException('Crawler alias not found');
        }
        return $this->classMap[$name];
    }

    /**
     * @param string $class
     * @param Client $client
     * @param Crawler $crawler
     * @param Restaurant $restaurant
     * @return AbstractCrawler
     */
    private function buildCrawler(string $class, Client $client, Crawler $crawler, Restaurant $restaurant): AbstractCrawler {
        return new $class($client, clone $crawler, $restaurant);
    }


}