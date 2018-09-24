<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MuzikumCrawler
 * @package App\DailyMenu\Crawler
 */
class MuzikumCrawler extends AbstractCrawler
{
    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $dayOfWeek = $date->format('N');
        $menuContent = $domCrawler->filter('.content-right div p')->eq($dayOfWeek-1)->html();

        $menuData = explode('<br>', $menuContent);

        if(count($menuData) < 2) {
            throw new InvalidArgumentException('Daily menu not found for this date');
        }

        $foods = $this->filterMenuData($menuData);

        $priceMatches = [];
        preg_match('/A menü ára ([0-9]+)/', $domCrawler->text(), $priceMatches);

        return new Menu($restaurant->getId(), $foods, $this->getPrice($domCrawler), $date);
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return 'http://muzikum.hu/heti-menu/';
    }

    private function getPrice(Crawler $domCrawler)
    {
        $priceMatches = [];
        preg_match('/A menü ára ([0-9]+)/', $domCrawler->text(), $priceMatches);
        return (int)$priceMatches[1];
    }

    private function filterMenuData(array $menuData)
    {
        return array_map('trim', $menuData);
    }
}