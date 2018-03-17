<?php

namespace App\Component\Film\Application\CommandHandler\Actor;

use App\Component\Film\Application\Command\Actor\CreateActorCommand;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;

class CreateActorHandler
{
    private $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    public function handle(CreateActorCommand $command): Actor
    {
        $actor = new Actor($command->name());
        $this->actorRepository->save($actor);

        return $actor;
    }

}