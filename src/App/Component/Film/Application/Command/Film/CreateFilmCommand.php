<?php

namespace App\Component\Film\Application\Command\Film;

use App\Component\Film\Domain\Actor;

class CreateFilmCommand
{
    private $name;
    private $description;
    private $actorId;

    public function __construct(string $name, string $description, int $actorId)
    {
        $this->name = $name;
        $this->description = $description;
        $this->actorId = $actorId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function actorId(): int
    {
        return $this->actorId;
    }

}