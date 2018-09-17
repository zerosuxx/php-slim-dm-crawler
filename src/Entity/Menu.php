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
     * @var string
     */
    private $drink;
    /**
     * @var string
     */
    private $dessert;

    /**
     * @param string $appetizer
     * @param string $mainCourse
     * @param string $drink
     * @param string $dessert
     */
    public function __construct(string $appetizer, string $mainCourse, string $drink = '', string $dessert = '')
    {

        $this->appetizer = $appetizer;
        $this->mainCourse = $mainCourse;
        $this->drink = $drink;
        $this->dessert = $dessert;
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
     * @return string
     */
    public function getDrink(): string
    {
        return $this->drink;
    }

    /**
     * @return string
     */
    public function getDessert(): string
    {
        return $this->dessert;
    }
}