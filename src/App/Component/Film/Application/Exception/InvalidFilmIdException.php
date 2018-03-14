<?php

namespace App\Component\Film\Application\Exception;

class InvalidFilmIdException extends InvalidArgumentException
{
    public static function empty()
    {
        return new static("The film's ID must be specified");
    }

}