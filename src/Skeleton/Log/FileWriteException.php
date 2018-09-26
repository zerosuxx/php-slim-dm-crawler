<?php

namespace App\Skeleton\Log;

use Psr\Log\InvalidArgumentException;
use Throwable;

/**
 * Class FileWriteException
 * @package App\Skeleton\Logger
 */
class FileWriteException extends InvalidArgumentException
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}