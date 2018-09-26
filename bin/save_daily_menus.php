<?php

use App\DailyMenu\Service\SaveDailyMenusService;
use Slim\App;

require_once dirname(__DIR__) . '/config/bootstrap.php';

(function(App $app, $arg) {
    $container = $app->getContainer();
    /* @var $saveDailyMenuService SaveDailyMenusService */
    $saveDailyMenuService = $container->get(SaveDailyMenusService::class);
    $date = isset($arg[1]) ? $arg[1] : null;
    $saveDailyMenuService->saveDailyMenus(new DateTime($date));
})(require dirname(__DIR__) . '/config/app.php', $argv);