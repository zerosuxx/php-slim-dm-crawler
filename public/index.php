<?php

use Slim\App;

require_once dirname(__DIR__) . '/config/bootstrap.php';

(function(App $app) {
    session_start();
    $app->run();
})(require dirname(__DIR__) . '/config/app.php');
