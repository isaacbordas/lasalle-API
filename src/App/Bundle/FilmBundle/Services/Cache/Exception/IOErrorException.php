<?php

namespace App\Bundle\FilmBundle\Services\Cache\Exception;
use Exception;
use Throwable;

class IOErrorException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}