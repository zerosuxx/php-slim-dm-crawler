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
     * @var int|null
     */
    private $id;

    /**
     * @param string $name
     * @param string $url
     * @param int|null $id
     */
    public function __construct(string $name, string $url, int $id = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->id = $id;
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCrawlerClass(): ?string
    {
        return $this->crawlerClass;
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
}