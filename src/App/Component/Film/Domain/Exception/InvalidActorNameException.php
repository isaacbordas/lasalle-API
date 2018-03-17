<?php

namespace App\Component\Film\Domain\Exception;


class InvalidActorNameException extends InvalidArgumentException
{
    public static function empty()
    {
        return new static("The actor's name must be specified");
    }
}