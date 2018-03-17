<?php

namespace App\Component\Film\Application\CommandHandler\Film;

use App\Component\Film\Application\Command\Film\CreateFilmCommand;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;
use App\Component\Film\Domain\Repository\ActorRepository;

class CreateFilmHandler
{
    private $filmRepository;
    private $actorRepository;

    public function __construct(FilmRepository $filmRepository, ActorRepository $actorRepository)
    {
        $this->filmRepository = $filmRepository;
        $this->actorRepository = $actorRepository;
    }

    public function handle(CreateFilmCommand $command): Film
    {

        $actor = $this->actorRepository->findById($command->actorId());

        $film = new Film($command->name(), $command->description(), $actor);
        $this->filmRepository->save($film);

        return $film;
    }
}