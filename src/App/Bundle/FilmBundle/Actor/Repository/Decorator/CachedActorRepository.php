<?php

namespace App\Bundle\FilmBundle\Actor\Repository\Decorator;

use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;
use App\Bundle\FilmBundle\Service\CacheService;

final class CachedActorRepository implements ActorRepository
{
   private $actorRepository;
   private $cache;

   public function __construct(ActorRepository $actorRepository, CacheService $cache)
   {
       $this->actorRepository = $actorRepository;
       $this->cache = $cache;
   }

   public function findById(int $actorId): Actor
   {
       $item = $this->cache->fetch((string) 'Actor' . $actorId);

       if (!$item) {
           $item = $this->actorRepository->findById($actorId);

           $this->cache->save((string) 'Actor' . $actorId, $item);
       }

       return $this->cache->fetch((string) 'Actor' . $actorId);
   }

    public function findAllOrderedByName()
    {
        $item = $this->cache->fetch((string) 'AllActors');

        if (!$item) {
            $item = $this->actorRepository->findAllOrderedByName();

            $this->cache->save((string) 'AllActors', $item);
        }

        return $this->cache->fetch((string) 'AllActors');
    }

   public function delete(Actor $actor): void
   {
       $item = $this->cache->fetch((string) 'Actor' . $actor->getId());

       if ($item) {
           $this->actorRepository->delete($actor);
           $this->cache->cacheClear((string) 'Actor' . $actor->getId());
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
           $this->cache->cacheClear((string) 'Actor' . $actor->getId());
       }

       $this->actorRepository->update($actor);
   }

}