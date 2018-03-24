<?php

namespace App\Component\Film\Domain\Exception;

use Throwable;

class UnknowFilmException extends BadOperationException
{
    public $filmId;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withFilmId(int $id):self
    {
        $e = new static("Film with id [$id] doesn't exist");
        $e->filmId = $id;
        return $e;
    }
}