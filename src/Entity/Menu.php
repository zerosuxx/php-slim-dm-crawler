<?php

namespace App\Entity;

/**
 * Class Menu
 * @package App\Entity
 */
class Menu
{
    /**
     * @var string
     */
    private $soup;
    /**
     * @var string
     */
    private $mainCourse;
    /**
     * @var int
     */
    private $price;
    /**
     * @var string|null
     */
    private $dessert = null;

    /**
     * @param string $soup
     * @param string $mainCourse
     * @param int|null $price
     */
    public function __construct(string $soup, string $mainCourse, int $price = null)
    {
        $this->soup = $soup;
        $this->mainCourse = $mainCourse;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getSoup(): string
    {
        return $this->soup;
    }

    /**
     * @return string
     */
    public function getMainCourse(): string
    {
        return $this->mainCourse;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param string $dessert
     * @return self
     */
    public function withDessert(string $dessert)
    {
        $new = clone $this;
        $new->dessert = $dessert;
        return $new;
    }

    /**
     * @return string|null
     */
    public function getDessert(): ?string
    {
        return $this->dessert;
    }
}