<?php

namespace Test\Skeleton\Log;

use App\Skeleton\Log\FileLogger;
use App\Skeleton\Log\FileWriteException;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Test\DailyMenu\DailyMenuSlimTestCase;

class FileLoggerTest extends DailyMenuSlimTestCase
{
    /**
     * @test
     */
    public function log_GivenValidDebugLevelAndContext_SaveLogFile()
    {
        $dir = sys_get_temp_dir();
        $fileName = 'error_log_%s_' . time() . mt_rand(1, 100000);
        $level = 'debug';
        $ext = 'log';

        $logger = new FileLogger($dir, $fileName);
        $logger->log($level, 'test');
        $logger->log($level, 'Hello {name}', ['name' => 'Test']);
        $logger->log($level, '{exception}', ['exception' => new \Exception('test')]);

        $logFile = $dir . '/' . sprintf($fileName, $level) . '.' . $ext;

        $contents = file_get_contents($logFile);
        $this->assertContains('test' . "\n", $contents);
        $this->assertContains('Hello Test' . "\n", $contents);
        $this->assertContains(__FILE__, $contents);
        $this->assertContains('Exception: test', $contents);
        unlink($logFile);
    }

    /**
     * @test
     */
    public function log_GivenNotValidFile_ThrowsException()
    {
        $this->expectException(FileWriteException::class);
        $dir = sys_get_temp_dir() . '/%s';
        $fileName = 'log';

        $logger = new FileLogger($dir, $fileName);
        $logger->log(LogLevel::ALERT, 'test');
    }

    /**
     * @test
     */
    public function log_GivenInvalidDebugLevel_ThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $logger = new FileLogger('');
        $logger->log('INVALID LEVEL', 'test');
    }
}