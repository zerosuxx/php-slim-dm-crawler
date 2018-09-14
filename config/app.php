<?php

use App\AppBuilder;

return (function() {
    $container = require __DIR__ . '/container.php';
    $providers = require __DIR__ . '/providers.php';

    $appBuilder = new AppBuilder();
    $providers($appBuilder);

    return $appBuilder->buildApp($container);
})();