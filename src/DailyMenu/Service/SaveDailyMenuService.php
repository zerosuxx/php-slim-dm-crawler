<?php

namespace App\DailyMenu\Service;

use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SaveDailyMenuService
 * @package App\DailyMenu\Service
 */
class SaveDailyMenuService
{
    /**
     * @var RestaurantsDao
     */
    private $restaurantsDao;
    /**
     * @var MenusDao
     */
    private $menusDao;
    /**
     * @var CrawlerFactory
     */
    private $crawlerFactory;

    /**
     * @param RestaurantsDao $restaurantsDao
     * @param MenusDao $menusDao
     * @param CrawlerFactory $crawlerFactory
     */
    public function __construct(RestaurantsDao $restaurantsDao, MenusDao $menusDao, CrawlerFactory $crawlerFactory)
    {
        $this->restaurantsDao = $restaurantsDao;
        $this->menusDao = $menusDao;
        $this->crawlerFactory = $crawlerFactory;
    }

    /**
     * @param \DateTime $date
     */
    public function saveDailyMenus(\DateTime $date)
    {
        $restaurants = $this->restaurantsDao->getRestaurants();
        foreach($restaurants as $restaurant) {
            try {
                $crawler = $this->crawlerFactory->createCrawlerFromName($restaurant->getName());
                $menu = $crawler->getDailyMenu($date)->withRestaurantId($restaurant->getId());
                $this->menusDao->save($menu);
            } catch (GuzzleException $exception) {

            }
        }
    }
}