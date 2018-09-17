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
    private $foods;
    /**
     * @var int|null
     */
    private $price;

    /**
     * @param string $foods
     * @param int|null $price [optional]
     */
    public function __construct(string $foods, int $price = null)
    {
        $this->foods = $foods;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getFoods(): string
    {
        return $this->foods;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }
}