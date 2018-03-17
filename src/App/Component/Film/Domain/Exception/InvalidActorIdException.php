<?php

namespace App\Component\Film\Domain\Exception;

class InvalidActorIdException extends InvalidArgumentException
{
    public static function empty()
    {
        return new static("The actor's ID must be specified");
    }

}