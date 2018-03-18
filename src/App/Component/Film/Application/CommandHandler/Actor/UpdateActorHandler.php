<?php

namespace App\Component\Film\Application\CommandHandler\Actor;

use App\Component\Film\Application\Command\Actor\UpdateActorCommand;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;

class UpdateActorHandler
{
    private $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    public function handle(UpdateActorCommand $command): Actor
    {
        $actor = $this->actorRepository->findById($command->actorId(), false);
        $actor->setName($command->name());
        $this->actorRepository->update($actor);

        return $actor;
    }

}