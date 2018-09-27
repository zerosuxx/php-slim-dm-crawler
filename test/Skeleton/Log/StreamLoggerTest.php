<?php

namespace Test\Skeleton\Log;

use App\Skeleton\Log\StreamLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Test\DailyMenu\DailyMenuSlimTestCase;

class StreamLoggerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function log_GivenValidDebugLevelAndContext_SaveLogFile()
    {
        $level = LogLevel::ERROR;
        $stream = fopen('php://memory', 'wb+');
        $logger = new StreamLogger($stream);
        $logger->log($level, 'test');
        $logger->log($level, 'Hello {name}', ['name' => 'Test']);
        $logger->log($level, '{exception}', ['exception' => new \Exception('test')]);

        rewind($stream);
        $contents = stream_get_contents($stream);
        $this->assertContains('test' . "\n", $contents);
        $this->assertContains('Hello Test' . "\n", $contents);
        $this->assertContains(__FILE__, $contents);
        $this->assertContains('Exception: test', $contents);
    }

    /**
     * @test
     */
    public function log_GivenInvalidDebugLevel_ThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $logger = new StreamLogger('php://memory');
        $logger->log('INVALID LEVEL', 'test');
    }
}