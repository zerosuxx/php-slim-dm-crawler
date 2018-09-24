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
        $day = $date->format('d');

        $column = $this->getColumn($domCrawler, $day);

        $dailyMenu = $domCrawler->filter('#'.$column.'_column table')->eq($dayOfWeek-1);

        $foods = $this->getFoods($dailyMenu);

        $title = $domCrawler->filter('h2')->text();
        preg_match('/([0-9]+)/', $title, $titleMatches);
        $price = (int)$titleMatches[1];

        return new Menu($restaurant->getId(), $foods, $price, $date);
    }

    protected function getUrl()
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
        throw new InvalidArgumentException('Daily menu not found for this date');
    }

    /**
     * @param $domCrawler
     * @return array
     */
    private function getFoods(Crawler $domCrawler): array
    {
        $soup = trim($domCrawler->filter('tr:nth-child(2) td:nth-child(3)')->text());
        $mainCourse = trim($domCrawler->filter('tr:nth-child(3) td:nth-child(3)')->text());
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
}