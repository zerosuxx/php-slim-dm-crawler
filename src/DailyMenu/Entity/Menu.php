<?php

namespace App\DailyMenu\Entity;

/**
 * Class Menu
 * @package App\DailyMenu\Entity
 */
class Menu
{
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
     * @var int
     */
    private $restaurantId;

    /**
     * @param \DateTime $date
     * @param array $foods
     * @param int|null $price [optional]
     */
    public function __construct(\DateTime $date, array $foods, int $price = null)
    {
        $this->date = $date;
        $this->foods = $foods;
        $this->price = $price;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
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

    /**
     * @return int
     */
    public function getRestaurantId(): int
    {
        return $this->restaurantId;
    }

    /**
     * @param int $restaurantId
     * @return self
     */
    public function withRestaurantId(int $restaurantId)
    {
        $new = clone $this;
        $new->restaurantId = $restaurantId;
        return $new;
    }
}