<?php

namespace App\Component\Film\Application\UseCase\Film;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Bundle\FilmBundle\EventSubscriber\DeleteCache;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\ActorRepository;
use App\Component\Film\Application\Exception\InvalidFilmNameException;

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

    public function execute($name, $description, $actorId, Film $film)
    {
        if(empty($name)) {
            throw InvalidFilmNameException::empty();
        }

        $actor = $this->actorRepository->findById($actorId);

        $film->setName($name);
        $film->setDescription($description);
        $film->setActor($actor);

        $this->entityManager->flush();

        $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache());
    }
}