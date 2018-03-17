<?php

namespace App\Component\Film\Application\Command\Film;

class UpdateFilmCommand
{
    private $filmId;
    private $name;
    private $description;
    private $actorId;

    public function __construct(string $name, string $description, int $actorId, int $filmId)
    {
        $this->filmId = $filmId;
        $this->name = $name;
        $this->description = $description;
        $this->actorId = $actorId;
    }

    public function filmId(): string
    {
        return $this->filmId;
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