<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NikaCrawler
 * @package App\DailyMenu\Crawler
 */
class NikaCrawler extends AbstractCrawler
{
    /**
     * @param Restaurant $restaurant
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return Menu
     */
    protected function createMenu(Restaurant $restaurant, DateTime $date, Crawler $domCrawler): Menu
    {
        $dayNum = null;
        $postIndex = null;
        $posts = $domCrawler->filter('[data-sigil="expose"]');
        foreach($posts as $index => $postElement) {
            $dayNum = $this->getDayNum($date, $postElement);
            if(null !== $dayNum) {
                $postIndex = $index;
                break;
            }
        }

        if(!$postIndex) {
            throw new \InvalidArgumentException('Daily menu not found for this date');
        }

        $post = $posts->eq($postIndex);

        $menuData = $this->getMenuData($post, $dayNum);

        $foods = $this->getFoods($menuData);

        return new Menu($restaurant->getId(), $foods, $this->getPrice($post), $date);
    }

    /**
     * @param Crawler $post
     * @param int $dayNum
     * @return mixed
     */
    private function getMenuData(Crawler $post, int $dayNum)
    {
        $menus = array_slice(iterator_to_array($post->filter('p')->getIterator()), 1);
        $dayChunks = array_chunk($menus, 2);
        return $dayChunks[$dayNum];
    }

    /**
     * @param Crawler $post
     * @return int
     */
    private function getPrice(Crawler $post): int
    {
        $priceMatches = [];
        preg_match('/Ár:.*([0-9]+)Ft/Us', $post->text(), $priceMatches);
        return (int)$priceMatches[1];
    }

    /**
     * @param \DOMElement[] $menuData
     * @return array
     */
    protected function getFoods(array $menuData)
    {
        $foods = array_slice(explode(' - ', $menuData[0]->textContent), 1);
        $dessert = explode(' - ', $menuData[1]->textContent, 2);
        $foods[] = 'Desszert: ' . $dessert[1];
        return $foods;
    }

    /**
     * @param DateTime $date
     * @param $postElement
     * @return int
     */
    protected function getDayNum(DateTime $date, \DOMElement $postElement): ?int
    {
        $dayNum = null;
        if (strpos($postElement->textContent, $date->format('Y.m.d')) !== false) {
            $dayNum = 0;
        } elseif (strpos($postElement->textContent, $date->format('m.d.')) !== false) {
            $dayNum = 1;
        }
        return $dayNum;
    }
}