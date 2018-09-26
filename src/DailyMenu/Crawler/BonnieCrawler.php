<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BonnieCrawler
 * @package App\DailyMenu\Crawler
 */
class BonnieCrawler extends AbstractCrawler
{

    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $foods = $this->getFoods($domCrawler, $date);
        $price = $this->gerPrice($domCrawler);
        return new Menu($restaurant->getId(), $foods, $price, $date);
    }

    protected function getUrl(): string
    {
        return 'http://bonnierestro.hu/hu/napimenu/';
    }

    /**
     * @param Crawler $domCrawler
     * @param int $day
     * @return string|null left|right
     * @throws \InvalidArgumentException
     */
    private function getColumn(Crawler $domCrawler, int $day)
    {
        $columns = ['left', 'right'];
        foreach($columns as $index => $column) {
            $dateTitle = $domCrawler->filter('h4')->eq($index)->text();

            $dateIntervalMatches = [];
            preg_match('/([0-9]{1,2}) -[^0-9]+([0-9]{1,2})/', $dateTitle, $dateIntervalMatches);

            if( $this->isInDateInterval($day, $dateIntervalMatches) ) {
                return $column;
            }
        }
        throw new CrawlerException('Daily menu not found for this date');
    }

    /**
     * @param Crawler $domCrawler
     * @param DateTime $date
     * @return array
     */
    private function getFoods(Crawler $domCrawler, DateTime $date): array
    {
        $dayOfWeek = $date->format('N');
        $day = $date->format('d');

        $column = $this->getColumn($domCrawler, $day);

        $menuElement = $domCrawler->filter('#'.$column.'_column table')->eq($dayOfWeek-1);

        $soup = trim($menuElement->filter('tr:nth-child(2) td:nth-child(3)')->text());
        $mainCourse = trim($menuElement->filter('tr:nth-child(3) td:nth-child(3)')->text());
        return [$soup, $mainCourse];
    }

    /**
     * @param int $day
     * @param array $days
     * @return bool
     */
    private function isInDateInterval(int $day, array $days): bool
    {
        if($days[1] > $days[2]) {
            return $days[1] <= $day || $days[2] >= $day;
        }

        return $days[1] <= $day && $days[2] >= $day;
    }

    /**
     * @param Crawler $domCrawler
     * @return int
     */
    private function gerPrice(Crawler $domCrawler): int
    {
        $title = $domCrawler->filter('h2')->text();
        $titleMatches = [];
        preg_match('/([0-9]+)/', $title, $titleMatches);
        $price = (int)$titleMatches[1];
        return $price;
    }
}