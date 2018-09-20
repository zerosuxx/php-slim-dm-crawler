<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class KajaHuCrawler
 * @package App\DailyMenu\Crawler
 */
class KajaHuCrawler extends AbstractCrawler
{
    /**
     * @param Restaurant $restaurant
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return Menu
     */
    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $data = json_decode($domCrawler->text());
        $dateString = $date->format('Y-m-d');
        foreach($data->jdata as $menu) {
            if($menu->ddate === $dateString) {
                $price = filter_var($menu->price, FILTER_SANITIZE_NUMBER_INT);
                return new Menu($restaurant->getId(), [$menu->line1, $menu->line2, $menu->line3], $price, $date);
            }
        }
        throw new \InvalidArgumentException('Daily menu not found for this date');
    }
}