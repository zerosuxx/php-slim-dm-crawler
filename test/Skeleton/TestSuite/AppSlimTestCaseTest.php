<?php

namespace Test\Skeleton\TestSuite;

use App\AppBuilder;
use App\Skeleton\TestSuite\AppSlimTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

/**
 * Class AbstractSlimTestCaseTest
 * @package Test\App\TestSuite
 */
class AppSlimTestCaseTest extends AppSlimTestCase
{

    protected function initializeApp(App $app)
    {
        parent::initializeApp($app);
    }

    /**
     * @test
     */
    public function getService_ReturnsTestServiceValue()
    {
        $this->assertEquals(1, $this->getService('test'));
    }

    /**
     * @test
     */
    public function runApp_WithPostRequestAndUnsetSlimErrorHandlers_ReturnsNewResponse()
    {
        $this->disableSlimErrorHandler(true);
        $postData = ['test' => 1];
        $response = $this->runApp('POST', '/', $postData);
        $app = $this->getApp();
        $this->assertFalse($app->getContainer()->has('errorHandler'));
        $this->assertFalse($app->getContainer()->has('phpErrorHandler'));
        $app->add(function(RequestInterface $request) use($postData) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('/', $request->getUri()->getPath());
            $this->assertEquals($postData, $request->getParsedBody());
        });
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     */
    public function getPDO_ReturnsPDOInstance()
    {
        $pdo = $this->getPDO();
        $this->assertInstanceOf(\PDO::class, $pdo);
    }

    /**
     * @test
     */
    public function truncateTable_TruncateTestTable()
    {
        /* @var $pdo MockObject */
        $pdo = $this->getPDO();
        $pdo
            ->expects($this->once())
            ->method('query')
            ->with('TRUNCATE TABLE test');
        $this->truncateTable('test');
    }

    /**
     * @test
     */
    public function mockService_ReplaceOriginalServiceWithMock()
    {
        $container = $this->getApp()->getContainer();
        $container['testMockService'] = 1;
        $mock = $this->createMock(\stdClass::class);
        $this->mockService($container, 'testMockService', $mock);
        $container->has('original_testMockService');
        $this->assertSame($mock, $container->get('testMockService'));
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
            $container['pdo'] = $this->createMock('PDO');
        });
    }
}