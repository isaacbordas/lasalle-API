<?php

namespace App\Component\Film\Application\CommandHandler\Film;

use App\Component\Film\Application\Command\Film\DeleteFilmCommand;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;

class DeleteFilmHandler
{
    private $filmRepository;

    public function __construct(FilmRepository $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    public function handle(DeleteFilmCommand $command): Film
    {
        $film = $this->filmRepository->findById($command->filmId());
        $this->filmRepository->delete($film);

        return $film;
    }

}