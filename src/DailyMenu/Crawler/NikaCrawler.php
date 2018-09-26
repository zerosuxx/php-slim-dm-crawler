<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use App\DailyMenu\Entity\Restaurant;
use DateTime;
use DOMElement;
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
        list($post, $dayNum) = $this->getPostAndDayNum($domCrawler, $date);

        $menuData = $this->getMenuData($post, $dayNum);

        $foods = $this->getFoods($menuData);

        return new Menu($restaurant->getId(), $foods, $this->getPrice($post), $date);
    }

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return 'https://iphone.facebook.com/nikadelimenu/posts/?ref=page_internal&mt_nav=0';
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
     * @param DOMElement[] $menuData
     * @return array
     */
    private function getFoods(array $menuData)
    {
        $foods = array_slice(explode(' - ', $menuData[0]->textContent), 1);
        $dessert = explode(' - ', $menuData[1]->textContent, 2);
        $foods[] = 'Desszert: ' . $dessert[1];
        return $foods;
    }

    /**
     * @param DateTime $date
     * @param DOMElement $postElement
     * @return int
     */
    private function getDayNum(DateTime $date, DOMElement $postElement): ?int
    {
        $dayNum = null;
        if (strpos($postElement->textContent, $date->format('Y.m.d')) !== false) {
            $dayNum = 0;
        } elseif (strpos($postElement->textContent, $date->format('m.d.')) !== false) {
            $dayNum = 1;
        }
        return $dayNum;
    }

    /**
     * @param DateTime $date
     * @param Crawler $domCrawler
     * @return array
     * @throws CrawlerException
     */
    private function getPostAndDayNum(Crawler $domCrawler, DateTime $date): array
    {
        $dayNum = null;
        $posts = $domCrawler->filter('[data-sigil="expose"]');
        foreach ($posts as $index => $postElement) {
            $dayNum = $this->getDayNum($date, $postElement);
            if (null !== $dayNum) {
                return [$posts->eq($index), $dayNum];
            }
        }
        throw new CrawlerException('Daily menu not found for this date');
    }
}