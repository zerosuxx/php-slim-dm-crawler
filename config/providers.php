<?php

use App\AppBuilder;
use App\DailyMenu\ConfigProvider;

return function(AppBuilder $appBuilder) {
    $appBuilder->addProvider(new ConfigProvider());
};