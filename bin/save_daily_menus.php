<?php

use Slim\App;

require_once dirname(__DIR__) . '/config/bootstrap.php';

(function(App $app) {
    $container = $app->getContainer();
    /* @var $saveDailyMenuService \App\DailyMenu\Service\SaveDailyMenuService */
    $saveDailyMenuService = $container->get(\App\DailyMenu\Service\SaveDailyMenuService::class);
    $saveDailyMenuService->saveDailyMenus(new DateTime());
})(require dirname(__DIR__) . '/config/app.php');