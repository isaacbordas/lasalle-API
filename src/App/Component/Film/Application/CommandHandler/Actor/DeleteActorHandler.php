<?php

namespace App\Component\Film\Application\CommandHandler\Actor;

use App\Component\Film\Application\Command\Actor\DeleteActorCommand;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;

class DeleteActorHandler
{
    private $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    public function handle(DeleteActorCommand $command): Actor
    {
        $actor = $this->actorRepository->findById($command->actorId());
        $this->actorRepository->delete($actor);

        return $actor;
    }

}