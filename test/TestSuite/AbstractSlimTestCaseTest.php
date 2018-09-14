<?php

namespace Test\TestSuite;

use App\AppBuilder;
use App\TestSuite\AbstractSlimTestCase;
use Slim\App;

/**
 * Class AbstractSlimTestCaseTest
 * @package Test\App\TestSuite
 */
class AbstractSlimTestCaseTest extends AbstractSlimTestCase
{
    /**
     * @test
     */
    public function test_getApp_ReturnsAppInstance()
    {
        $app = $this->getApp();
        $this->assertEquals(1, $app->getContainer()->get('test'));
    }

    /**
     * @param AppBuilder $appBuilder
     * @return void
     */
    protected function addProvider(AppBuilder $appBuilder)
    {
        $appBuilder->addProvider(function(App $app) {
            $container = $app->getContainer();
            $container['test'] = 1;
        });
    }
}