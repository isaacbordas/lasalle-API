<?php

namespace App\Bundle\FilmBundle\Services\Cache\Exception;

use Psr\SimpleCache\CacheException;
use Exception as PHPException;
use Throwable;

class Exception extends PHPException implements CacheException
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}