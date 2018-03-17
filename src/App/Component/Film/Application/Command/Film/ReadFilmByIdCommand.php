<?php

namespace App\Component\Film\Application\Command\Film;

class ReadFilmByIdCommand
{
    private $filmId;

    public function __construct(int $filmId)
    {
        $this->filmId = $filmId;
    }

    public function filmId(): int
    {
        return $this->filmId;
    }
}