<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BonnieCrawler
 * @package App\DailyMenu\Crawler
 */
class BonnieCrawler extends AbstractCrawler
{



    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $dayOfWeek = $date->format('N');

        $dailyMenu = $domCrawler->filter('#left_column table')->eq($dayOfWeek-1);
        if(!$dailyMenu->count()) {
            throw new InvalidArgumentException('Daily menu not found for this date');
        }

        $soup = trim($dailyMenu->filter('tr:nth-child(2) td:nth-child(3)')->text());
        $mainCourse = trim($dailyMenu->filter('tr:nth-child(3) td:nth-child(3)')->text());

        $title = $domCrawler->filter('h2')->text();
        preg_match('/([0-9]+)/', $title, $titleMatches);
        $price = (int)$titleMatches[1];

        return new Menu($restaurant->getId(), [$soup, $mainCourse], $price, $date);
    }
}