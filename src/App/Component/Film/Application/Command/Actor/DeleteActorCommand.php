<?php

namespace App\Component\Film\Application\Command\Actor;

class DeleteActorCommand
{
    private $actorId;

    public function __construct(int $actorId)
    {
        $this->actorId = $actorId;
    }

    public function actorId(): int
    {
        return $this->actorId;
    }

}