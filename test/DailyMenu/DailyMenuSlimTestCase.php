<?php

namespace Test\DailyMenu;

use App\AppBuilder;
use App\DailyMenu\ConfigProvider;
use App\Skeleton\TestSuite\AppSlimTestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slim\App;

/**
 * Class DailyMenuSlimTestCase
 * @package Test\App
 */
class DailyMenuSlimTestCase extends AppSlimTestCase
{

    protected function initializeApp(App $app)
    {
        parent::initializeApp($app);
        $this->mockService($app->getContainer(), 'client', $this->createMock(Client::class));
    }

    /**
     * @param AppBuilder $appBuilder
     * @return void
     */
    protected function addProvider(AppBuilder $appBuilder)
    {
        parent::addProvider($appBuilder);
        $appBuilder->addProvider(new ConfigProvider());
    }

    protected function createClientMock(string $file) {
        $bodyMock = $this->createMock(StreamInterface::class);
        $bodyMock
            ->expects($this->once())
            ->method('__toString')
            ->willReturn(file_get_contents($file));

        $responseMock = $this->createMock(ResponseInterface::class);

        $responseMock
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($bodyMock);

        $clientMock = $this->createMock(Client::class);
        $clientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);
        return $clientMock;
    }
}