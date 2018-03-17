<?php

namespace App\Component\Film\Application\Command\Film;

class DeleteFilmCommand
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