<?php

namespace App\DailyMenu\Service;

use App\DailyMenu\Crawler\CrawlerFactory;
use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Dao\RestaurantsDao;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param RestaurantsDao $restaurantsDao
     * @param MenusDao $menusDao
     * @param CrawlerFactory $crawlerFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        RestaurantsDao $restaurantsDao,
        MenusDao $menusDao,
        CrawlerFactory $crawlerFactory,
        LoggerInterface $logger
    ) {
        $this->restaurantsDao = $restaurantsDao;
        $this->menusDao = $menusDao;
        $this->crawlerFactory = $crawlerFactory;
        $this->logger = $logger;
    }

    /**
     * @param DateTime $date
     */
    public function saveDailyMenus(DateTime $date)
    {
        $restaurants = $this->restaurantsDao->getDailyRestaurants($date);
        foreach($restaurants as $restaurant) {
            try {
                $crawler = $this->crawlerFactory->getCrawlerFromRestaurantName($restaurant->getName());
                $menu = $crawler->getDailyMenu($date);
                $this->menusDao->save($menu);
            } catch (\Throwable|GuzzleException $exception) {
                $this->logger->error('[{date}] {exception}', [
                    'date' => date('Y-m-d H:i:s'),
                    'exception' => $exception
                ]);
            }
        }
    }
}