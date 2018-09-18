<?php

namespace App\DailyMenu\Crawler;

use App\DailyMenu\Entity\Menu;
use DateTime;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BonnieCrawler
 * @package App\DailyMenu\Crawler
 */
class BonnieCrawler extends AbstractCrawler
{

    const DEFAULT_URL = 'http://bonnierestro.hu/hu/napimenu/';

    /**
     * @param Client $client
     * @param Crawler $domCrawler
     * @param string $url [optional]
     */
    public function __construct(Client $client, Crawler $domCrawler, $url = self::DEFAULT_URL)
    {
        parent::__construct($client, $domCrawler, $url);
    }

    protected function createMenu(DateTime $date, Crawler $domCrawler): Menu
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

        return new Menu($date, [$soup, $mainCourse], $price);
    }
}