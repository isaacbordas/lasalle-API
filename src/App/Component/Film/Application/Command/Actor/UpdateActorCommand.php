<?php

namespace App\Component\Film\Application\Command\Actor;

class UpdateActorCommand
{
    private $actorId;
    private $name;

    public function __construct(string $name, int $actorId)
    {
        $this->name = $name;
        $this->actorId = $actorId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function actorId(): int
    {
        return $this->actorId;
    }

}