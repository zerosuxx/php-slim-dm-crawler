<?php

use Slim\Container;

return (function() {
    $config = require __DIR__ . '/config.php';

    $container = new Container($config);

    return $container;
})();