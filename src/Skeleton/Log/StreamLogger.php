<?php

namespace App\Skeleton\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;

/**
 * Class StreamLogger
 * @package App\Skeleton\Logger
 */
class StreamLogger extends AbstractLogger
{
    use LoggerTrait;

    /**
     * @var string
     */
    private $stream;

    /**
     * @param $stream
     */
    public function __construct($stream)
    {
        if (is_string($stream)) {
            $stream = fopen($stream, 'wb+');
        }
        $this->stream = $stream;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context [optional]
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = [])
    {
        $this->validateLevel($level);

        $context['level'] = $level;
        $message = $this->interpolate($message, $context);

        fwrite($this->stream, $message . "\n");
    }
}