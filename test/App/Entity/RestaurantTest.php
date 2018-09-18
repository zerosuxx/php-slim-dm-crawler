<?php

namespace Test\App\Entity;

use App\DailyMenu\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class RestaurantTest extends TestCase
{

    /**
     * @test
     */
    public function getName_ReturnsName()
    {
        $restaurant = new Restaurant('Test name', '');
        $this->assertEquals('Test name', $restaurant->getName());
    }

    /**
     * @test
     */
    public function getUrl_ReturnsUrl()
    {
        $restaurant = new Restaurant('', 'http://test.test');
        $this->assertEquals('http://test.test', $restaurant->getUrl());
    }

    /**
     * @test
     */
    public function getCrawlerClass_ReturnsCrawlerClass()
    {
        $restaurant = new Restaurant('', '');
        $this->assertEquals('TestClass', $restaurant->withCrawlerClass('TestClass')->getCrawlerClass());
        $this->assertNull($restaurant->getCrawlerClass());
    }

}