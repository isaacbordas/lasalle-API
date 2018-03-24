<?php

namespace App\Bundle\FilmBundle\Services\Cache\Exception;

use Psr\SimpleCache\InvalidArgumentException as InvalidArgumentExceptionInterface;
use Exception;
use Throwable;

class InvalidArgumentException extends Exception implements InvalidArgumentExceptionInterface
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}