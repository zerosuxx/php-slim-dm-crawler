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
     * @param string $appetizer
     */
    public function __construct(string $appetizer)
    {

        $this->appetizer = $appetizer;
    }

    /**
     * @return string
     */
    public function getAppetizer(): string
    {
        return $this->appetizer;
    }
}