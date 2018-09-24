<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class VendiakCrawler
 * @package App\DailyMenu\Crawler
 */
class VendiakCrawler extends AbstractCrawler
{

    /**
     * @param Restaurant $restaurant
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return Menu
     */
    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $menuContent = $domCrawler->filter('.offer-item')->eq(0)->html();
        $menuData = explode('<br>', $menuContent);

        if(count($menuData) < 2) {
            throw new \InvalidArgumentException('Daily menu not found for this date');
        }

        $cleanedData = $this->formatMenuData($menuData);
        $lastIndex = count($cleanedData) - 1;
        $price = filter_var($cleanedData[$lastIndex], FILTER_SANITIZE_NUMBER_INT);
        $foods = array_slice($cleanedData, 0, -1);

        return new Menu($restaurant->getId(), $foods, $price, $date);
    }

    /**
     * @param array $menuData
     * @return array
     */
    protected function formatMenuData(array $menuData): array
    {
        $trimValues = array_map('trim', $menuData);
        $filteredValues = array_filter($trimValues);
        return array_values($filteredValues);
    }


}