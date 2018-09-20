<?php

namespace App\DailyMenu\Action;

use App\DailyMenu\Dao\MenusDao;
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
        $date = new \DateTime(isset($args['date']) ? $args['date'] : null);
        $this->twig->render($response, 'menus.html.twig', [
            'menus' => $this->menusDao->getMenusByRestaurants($date)
        ]);
    }
}