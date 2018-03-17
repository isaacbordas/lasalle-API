<?php

namespace App\Component\Film\Domain\Exception;

class InvalidFilmDescriptionException extends InvalidArgumentException
{
    public static function empty()
    {
        return new static("The film's name must be specified");
    }
}