<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MuzikumCrawler
 * @package App\DailyMenu\Crawler
 */
class MuzikumCrawler extends AbstractCrawler
{
    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $menuData = $this->getMenuData($date, $domCrawler);

        if(count($menuData) < 2) {
            throw new CrawlerException('Daily menu not found for this date');
        }

        $foods = $this->filterMenuData($menuData);

        return new Menu($restaurant->getId(), $foods, $this->getPrice($domCrawler), $date);
    }

    /**
     * @return string
     */
    protected function getUrl(): string
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

    /**
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return array
     */
    private function getMenuData(DateTime $date, Crawler $domCrawler): array
    {
        $dayOfWeek = $date->format('N');

        $menuCrawler = $domCrawler->filter('.content-right div')->eq($dayOfWeek - 1)->filter('p');

        $menuContent = $menuCrawler->count() ? $menuCrawler->text() : '';

        $menuData = explode("\n", $menuContent);
        return $menuData;
    }
}