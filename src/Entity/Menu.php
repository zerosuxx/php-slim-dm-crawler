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
     * @param string $appetizer
     */
    public function __construct(string $appetizer, string $mainCourse)
    {

        $this->appetizer = $appetizer;
        $this->mainCourse = $mainCourse;
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
}