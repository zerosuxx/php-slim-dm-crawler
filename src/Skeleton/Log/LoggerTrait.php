<?php

namespace App\Skeleton\Log;

use Exception;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

trait LoggerTrait
{
    /**
     * @param $level
     * @throws InvalidArgumentException
     */
    protected function validateLevel($level)
    {
        if( !in_array($level, $this->getLevels()) ) {
            throw new InvalidArgumentException(sprintf('Invalid log level: %s', $level));
        }
    }

    /**
     * @return array
     */
    protected function getLevels(): array
    {
        return [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ];
    }

    /**
     * @param $message
     * @param array $context
     * @return string
     */
    protected function interpolate($message, array $context)
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if($key === 'exception' && $val instanceof Exception) {
                $val = $this->exceptionToString($val);
            }
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }

    /**
     * @param Exception $exception
     * @return string
     */
    protected function exceptionToString(Exception $exception)
    {
        return get_class($exception) . ': ' . $exception->getMessage() . "\n"
            . '## ' . $exception->getFile() . '('.$exception->getLine().')' . "\n"
            . $exception->getTraceAsString();
    }
}