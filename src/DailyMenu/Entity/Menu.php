<?php

namespace App\DailyMenu\Entity;

/**
 * Class Menu
 * @package App\DailyMenu\Entity
 */
class Menu
{
    /**
     * @var int
     */
    private $restaurantId;
    /**
     * @var \DateTime
     */
    private $date;
    /**
     * @var string
     */
    private $foods;
    /**
     * @var int|null
     */
    private $price;

    /**
     * @param int $restaurantId
     * @param array $foods
     * @param int|null $price [optional]
     * @param \DateTime $date [optional]
     */
    public function __construct(int $restaurantId, array $foods, int $price = null, \DateTime $date = null)
    {
        $this->restaurantId = $restaurantId;
        $this->foods = $foods;
        $this->price = $price;
        $this->date = $date ?: new \DateTime();
    }

    /**
     * @return int
     */
    public function getRestaurantId(): int
    {
        return $this->restaurantId;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return \DateTime
     */
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }

    /**
     * @return array
     */
    public function getFoods(): array
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