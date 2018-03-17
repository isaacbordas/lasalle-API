<?php

namespace App\Component\Film\Application\CommandHandler\Actor;

use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;

class ReadActorAllHandler
{
    private $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    public function handle(): array
    {
        $actors = $this->actorRepository->findAllOrderedByName();

        return $actors;
    }
}