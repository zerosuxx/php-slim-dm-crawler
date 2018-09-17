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
     * @param string $appetizer
     * @param string $mainCourse
     * @param string $drink
     */
    public function __construct(string $appetizer, string $mainCourse, string $drink)
    {

        $this->appetizer = $appetizer;
        $this->mainCourse = $mainCourse;
        $this->drink = $drink;
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
}