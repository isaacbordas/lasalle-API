<?php

namespace App\Component\Film\Application\CommandHandler\Film;

use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;

class ReadFilmAllHandler
{
    private $filmRepository;

    public function __construct(FilmRepository $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    public function handle(): array
    {
        $films = $this->filmRepository->findAllOrderedByName();

        return $films;
    }
}