<?php

use App\AppBuilder;

return function(AppBuilder $appBuilder) {
    $appBuilder->addProvider(new \App\Skeleton\ConfigProvider());
    $appBuilder->addProvider(new \App\DailyMenu\ConfigProvider());
};