<?php

namespace App\Component\Film\Application\CommandHandler\Film;

use App\Component\Film\Application\Command\Film\ReadFilmByIdCommand;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;

class ReadFilmByIdHandler
{
    private $filmRepository;

    public function __construct(FilmRepository $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    public function handle(ReadFilmByIdCommand $command): Film
    {
        $film = $this->filmRepository->findById($command->filmId());

        return $film;
    }
}