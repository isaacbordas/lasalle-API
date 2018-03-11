<?php

namespace App\Component\Film\Application\Exception;

use Exception;
use Throwable;

class InvalidArgumentException extends Exception
{
    protected function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}