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
        $menuData = $this->getMenuData($date, $domCrawler);

        if(count($menuData) < 2) {
            throw new CrawlerException('Daily menu not found for this date');
        }

        $cleanedData = $this->formatMenuData($menuData);
        $price = $this->getPrice($cleanedData);
        $foods = $this->getFoods($cleanedData);

        return new Menu($restaurant->getId(), $foods, $price, $date);
    }

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return 'http://www.vendiaketterem.hu/';
    }

    /**
     * @param array $menuData
     * @return array
     */
    private function formatMenuData(array $menuData): array
    {
        $trimValues = array_map('trim', $menuData);
        $filteredValues = array_filter($trimValues);
        return array_values($filteredValues);
    }

    /**
     * @param $menuData
     * @return int
     */
    private function getPrice(array $menuData): int
    {
        $lastIndex = count($menuData) - 1;
        $price = filter_var($menuData[$lastIndex], FILTER_SANITIZE_NUMBER_INT);
        return (int)$price;
    }

    /**
     * @param $menuData
     * @return array
     */
    protected function getFoods(array $menuData): array
    {
        return array_slice($menuData, 0, -1);
    }

    /**
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return array
     */
    private function getMenuData(DateTime $date, Crawler $domCrawler): array
    {
        $dayOfWeek = $date->format('N');
        $menuElement = $domCrawler->filter('.offer-item')->eq($dayOfWeek);
        $menuContent = $menuElement->count() ? $menuElement->text() : '';
        $menuData = explode("\n", $menuContent);
        return $menuData;
    }

}