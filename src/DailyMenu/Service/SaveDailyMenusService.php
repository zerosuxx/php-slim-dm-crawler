<?php

namespace App\DailyMenu\Service;

use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use GuzzleHttp\Exception\GuzzleException;
use PDOException;

/**
 * Class SaveDailyMenuService
 * @package App\DailyMenu\Service
 */
class SaveDailyMenusService
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
     * @var callable
     */
    private $logger;

    /**
     * @param RestaurantsDao $restaurantsDao
     * @param MenusDao $menusDao
     * @param CrawlerFactory $crawlerFactory
     * @param callable $logger
     */
    public function __construct(RestaurantsDao $restaurantsDao, MenusDao $menusDao, CrawlerFactory $crawlerFactory, callable $logger)
    {
        $this->restaurantsDao = $restaurantsDao;
        $this->menusDao = $menusDao;
        $this->crawlerFactory = $crawlerFactory;
        $this->logger = $logger;
    }

    /**
     * @param \DateTime $date
     */
    public function saveDailyMenus(\DateTime $date)
    {
        $logger = $this->logger;
        $restaurants = $this->restaurantsDao->getDailyRestaurants($date);
        foreach($restaurants as $restaurant) {
            try {
                $crawler = $this->crawlerFactory->getCrawlerFromRestaurantName($restaurant->getName());
                $menu = $crawler->getDailyMenu($date);
                $this->menusDao->save($menu);
            } catch (GuzzleException|PDOException $exception) {
                $logger('save_daily_menus_service_error', $exception);
            }
        }
    }
}