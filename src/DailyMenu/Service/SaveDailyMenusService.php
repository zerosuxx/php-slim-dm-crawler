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
        $restaurants = $this->restaurantsDao->getDailyRestaurants($date);
        foreach($restaurants as $restaurant) {
            try {
                $crawler = $this->crawlerFactory->getCrawlerFromRestaurantName($restaurant->getName());
                $menu = $crawler->getDailyMenu($date);
                $this->menusDao->save($menu);
            } catch (GuzzleException $exception) {
                error_log($this->parseError($exception));
            } catch (PDOException $exception) {
                error_log($this->parseError($exception));
            }
        }
    }

    private function parseError(\Exception $exception, $extraMessage = null)
    {
        return get_class($exception) . "\n"
            . ($extraMessage ? $extraMessage . "\n" : '')
            . '## ' . $exception->getFile() . '('.$exception->getLine().'): ' . $exception->getMessage() . "\n"
            . $exception->getTraceAsString();
    }
}