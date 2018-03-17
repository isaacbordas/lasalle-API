<?php

namespace App\Component\Film\Application\CommandHandler\Actor;

use App\Component\Film\Application\Command\Actor\ReadActorByIdCommand;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;

class ReadActorByIdHandler
{
    private $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    public function handle(ReadActorByIdCommand $command): Actor
    {
        $actor = $this->actorRepository->findById($command->actorId());

        return $actor;
    }

}