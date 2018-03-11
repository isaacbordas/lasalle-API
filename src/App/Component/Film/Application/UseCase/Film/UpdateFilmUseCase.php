<?php

namespace App\Component\Film\Application\UseCase\Film;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Bundle\FilmBundle\EventSubscriber\DeleteCache;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\ActorRepository;

class UpdateFilmUseCase
{
    private $actorRepository;
    private $entityManager;
    private $dispatcher;

    public function __construct(ActorRepository $actorRepository, EntityManager $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    public function execute(array $filmData, Film $film)
    {
        $actor = $this->actorRepository->findById($filmData['actorId']);

        $film->setName($filmData['name']);
        $film->setDescription($filmData['description']);
        $film->setActor($actor);

        $this->entityManager->flush();

        $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache());
    }
}