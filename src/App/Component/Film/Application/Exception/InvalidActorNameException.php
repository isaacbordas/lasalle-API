<?php

namespace App\Component\Film\Application\Exception;


class InvalidActorNameException extends InvalidArgumentException
{
    public static function empty()
    {
        return new static("The actor's name must be specified");
    }
}