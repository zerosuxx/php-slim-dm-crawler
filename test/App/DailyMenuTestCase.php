<?php

namespace Test\App;

use App\AppBuilder;
use App\DailyMenu\ConfigProvider;
use App\TestSuite\AbstractSlimTestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class DailyMenuTestCase
 * @package Test\App
 */
class DailyMenuTestCase extends AbstractSlimTestCase
{

    /**
     * @param AppBuilder $appBuilder
     * @return void
     */
    protected function addProvider(AppBuilder $appBuilder)
    {
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