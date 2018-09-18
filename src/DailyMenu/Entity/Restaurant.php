<?php

namespace App\DailyMenu\Entity;

/**
 * Class Restaurant
 * @package App\DailyMenu\Entity
 */
class Restaurant
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string|null
     */
    private $crawlerClass = null;

    /**
     * @param string $name
     * @param string $url
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $class
     * @return self
     */
    public function withCrawlerClass(string $class)
    {
        $new = clone $this;
        $new->crawlerClass = $class;
        return $new;
    }

    /**
     * @return string
     */
    public function getCrawlerClass(): ?string
    {
        return $this->crawlerClass;
    }
}