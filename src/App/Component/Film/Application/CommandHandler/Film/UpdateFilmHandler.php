<?php

namespace App\Component\Film\Application\CommandHandler\Film;

use App\Component\Film\Application\Command\Film\UpdateFilmCommand;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;
use App\Component\Film\Domain\Repository\ActorRepository;

class UpdateFilmHandler
{
    private $filmRepository;
    private $actorRepository;

    public function __construct(FilmRepository $filmRepository, ActorRepository $actorRepository)
    {
        $this->filmRepository = $filmRepository;
        $this->actorRepository = $actorRepository;
    }

    public function handle(UpdateFilmCommand $command): Film
    {
        $actor = $this->actorRepository->findById($command->actorId(), false);

        $film = $this->filmRepository->findById($command->filmId(), false);

        if(!empty($command->name())) $film->setName($command->name());
        if(!empty($command->description())) $film->setDescription($command->description());
        $film->setActor($actor);

        $this->filmRepository->update($film);

        return $film;
    }
}