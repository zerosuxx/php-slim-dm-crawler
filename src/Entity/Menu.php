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
    private $appetizer;
    /**
     * @var string
     */
    private $mainCourse;
    /**
     * @var int
     */
    private $price;

    /**
     * @param string $appetizer
     * @param string $mainCourse
     */
    public function __construct(string $appetizer, string $mainCourse, int $price = null)
    {
        $this->appetizer = $appetizer;
        $this->mainCourse = $mainCourse;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getAppetizer(): string
    {
        return $this->appetizer;
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
}