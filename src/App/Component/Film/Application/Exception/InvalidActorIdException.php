<?php

namespace App\Component\Film\Application\Exception;

class InvalidActorIdException extends InvalidArgumentException
{
    public static function empty()
    {
        return new static("The actor's ID must be specified");
    }

}