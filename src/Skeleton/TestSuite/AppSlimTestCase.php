<?php

namespace App\Skeleton\TestSuite;

use App\AppBuilder;
use App\Skeleton\ConfigProvider;

class AppSlimTestCase extends AbstractSlimTestCase
{
    /**
     * @param AppBuilder $appBuilder
     * @return void
     */
    protected function addProvider(AppBuilder $appBuilder)
    {
        $appBuilder->addProvider(new ConfigProvider());
    }
}