<?php

use App\DailyMenu\Service\SaveDailyMenusService;
use Slim\App;

require_once dirname(__DIR__) . '/config/bootstrap.php';

(function(App $app) {
    $container = $app->getContainer();
    /* @var $saveDailyMenuService SaveDailyMenusService */
    $saveDailyMenuService = $container->get(SaveDailyMenusService::class);
    $saveDailyMenuService->saveDailyMenus(new DateTime());
})(require dirname(__DIR__) . '/config/app.php');