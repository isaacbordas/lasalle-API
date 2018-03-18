<?php

namespace App\Bundle\FilmBundle\Actor\Repository\Decorator;

use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;
use App\Bundle\FilmBundle\Service\CacheService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Bundle\FilmBundle\EventSubscriber\DeleteCache;

final class CachedActorRepository implements ActorRepository
{
   private $actorRepository;
   private $cache;
   private $dispatcher;

   public function __construct(ActorRepository $actorRepository, CacheService $cache, EventDispatcherInterface $dispatcher)
   {
       $this->actorRepository = $actorRepository;
       $this->cache = $cache;
       $this->dispatcher = $dispatcher;
   }

   public function findById(int $actorId): Actor
   {
       $item = $this->cache->fetch((string) 'Actor' . $actorId);

       if (!$item) {
           $item = $this->actorRepository->findById($actorId);

           $this->cache->save((string) 'Actor' . $actorId, $item);
       }

       return $item;
   }

    public function findAllOrderedByName()
    {
        $item = $this->cache->fetch((string) 'AllActors');

        if (!$item) {
            $item = $this->actorRepository->findAllOrderedByName();

            $this->cache->save((string) 'AllActors', $item);
        }

        return $item;
    }

   public function delete(Actor $actor): void
   {
       $item = $this->cache->fetch((string) 'Actor' . $actor->getId());

       if ($item) {
           $this->actorRepository->delete($actor);
           $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'Actor' . $actor->getId()));
       }
   }

   public function save(Actor $actor): void
   {
       $this->actorRepository->save($actor);
   }

   public function update(Actor $actor): void
   {
       $item = $this->cache->fetch((string) 'Actor' . $actor->getId());

       if ($item) {
           $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'Actor' . $actor->getId()));
       }

       $this->actorRepository->update($actor);
   }

}