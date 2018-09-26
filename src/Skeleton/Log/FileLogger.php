<?php

namespace App\Skeleton\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;

/**
 * Class FileLogger
 * @package App\Skeleton\Logger
 */
class FileLogger extends AbstractLogger
{
    use LoggerTrait;
    /**
     * @var string
     */
    private $directory;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $extension;

    /**
     * @param string $directory
     * @param string $name [optional] default: log level (eg.: debug,notice,error...)
     * @param string $extension [optional] default: log
     */
    public function __construct(string $directory, string $name = '%s', $extension = 'log')
    {
        $this->directory = $directory;
        $this->name = $name;
        $this->extension = $extension;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context [optional]
     * @throws InvalidArgumentException
     * @throws FileWriteException
     */
    public function log($level, $message, array $context = [])
    {
        $this->validateLevel($level);
        $this->validateDirectory($this->directory);

        $ext = $this->extension ? '.' . $this->extension : '';
        $file = sprintf($this->directory, $level) . '/' . sprintf($this->name, $level) . $ext;
        $message = $this->interpolate($message, $context) . "\n";

        file_put_contents($file, $message, FILE_APPEND);
    }

    /**
     * @param $directory
     * @throws FileWriteException
     */
    protected function validateDirectory($directory)
    {
        if(!is_writable($directory)) {
            throw new FileWriteException(sprintf('The directory "%s" is not writable', $directory));
        }
    }
}