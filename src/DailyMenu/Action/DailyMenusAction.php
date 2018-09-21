<?php

namespace App\DailyMenu\Action;

use App\DailyMenu\Dao\MenusDao;
use App\DailyMenu\Entity\Menu;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * Class DailyMenusAction
 * @package App\DailyMenu\Action
 */
class DailyMenusAction
{
    /**
     * @var MenusDao
     */
    private $menusDao;
    /**
     * @var Twig
     */
    private $twig;

    public function __construct(MenusDao $menusDao, Twig $twig)
    {
        $this->menusDao = $menusDao;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $startDate = new \DateTime(isset($args['startDate']) ? $args['startDate'] : null);
        $endDate = isset($args['endDate']) ? new \DateTime($args['endDate']) : $startDate;

        $menusByRestaurants = $this->menusDao->getMenusBetweenDatesByRestaurants($startDate, $endDate);

        $this->twig->render($response, 'menus.html.twig', [
            'menus' => $this->getMenusByDates($menusByRestaurants)
        ]);
    }

    private function getMenusByDates(array $menusByRestaurants) {
        $menusByDates = [];
        foreach ($menusByRestaurants as $restaurant => $menus) {
            /* @var $menus Menu[] */
            foreach($menus as $menu) {
                $date = $menu->getDate()->format('Y-m-d');
                $menusByDates[$date][$restaurant] = $menu;
            }
        }
        return $menusByDates;
    }
}