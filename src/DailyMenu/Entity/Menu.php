<?php

namespace App\DailyMenu\Entity;

/**
 * Class Menu
 * @package App\DailyMenu\Entity
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
     * @var int
     */
    private $restaurantId;

    /**
     * @param string $foods
     * @param int|null $price [optional]
     * @param int|null $restaurantId
     */
    public function __construct(string $foods, int $price = null, int $restaurantId = null)
    {
        $this->foods = $foods;
        $this->price = $price;
        $this->restaurantId = $restaurantId;
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

    /**
     * @return int
     */
    public function getRestaurantId(): int
    {
        return $this->restaurantId;
    }
}