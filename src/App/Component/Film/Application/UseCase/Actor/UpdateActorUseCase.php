<?php

namespace App\Component\Film\Application\UseCase\Actor;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Bundle\FilmBundle\EventSubscriber\DeleteCache;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Application\Exception\InvalidActorNameException;

class UpdateActorUseCase
{
    private $entityManager;
    private $dispatcher;

    public function __construct(EntityManager $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    public function execute(string $name, Actor $actor)
    {
        if(empty($name)) {
            throw InvalidActorNameException::empty();
        }

        $actor->setName($name);

        $this->entityManager->flush();

        $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache());
    }
}