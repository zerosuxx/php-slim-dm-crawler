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
     * @var int|null
     */
    private $restaurantId = null;
    /**
     * @var string|null
     */
    private $restaurantName = null;

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
     * @return \DateTime
     */
    public function getDateInTimestamp(): string
    {
        return $this->date->format('Y-m-d H:i:s');
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
    public function getRestaurantId(): ?int
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

    /**
     * @param string $restaurantName
     * @return self
     */
    public function withRestaurantName(string $restaurantName)
    {
        $new = clone $this;
        $new->restaurantName = $restaurantName;
        return $new;
    }

    /**
     * @return string
     */
    public function getRestaurantName(): ?string
    {
        return $this->restaurantName;
    }
}