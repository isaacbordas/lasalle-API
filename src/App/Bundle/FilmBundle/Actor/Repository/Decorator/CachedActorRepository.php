<?php

namespace App\Bundle\FilmBundle\Actor\Repository\Decorator;

use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;
use App\Bundle\FilmBundle\Services\Cache\CacheService;
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

   public function findById(int $actorId, bool $cache = true): Actor
   {
       if ($cache == false) {
           $item = $this->actorRepository->findById($actorId);
       } else {
           $item = $this->cache->get((string)'actor' . $actorId);

           if (!$item) {
               $item = $this->actorRepository->findById($actorId);

               $this->cache->set((string)'actor' . $actorId, $item);
           }
       }

       return $item;
   }

    public function findAllOrderedByName(bool $cache = true)
    {
        $item = $this->cache->get((string) 'allactors');

        if (!$item) {
            $item = $this->actorRepository->findAllOrderedByName();

            $this->cache->set((string) 'allactors', $item);
        }

        return $item;
    }

   public function delete(Actor $actor): void
   {
       $item = $this->cache->get((string) 'actor' . $actor->getId());

       if ($item) {
           $this->actorRepository->delete($actor);
           $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'actor' . $actor->getId()));
           $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'allactors'));
       }
   }

   public function save(Actor $actor): void
   {
       $this->actorRepository->save($actor);
       $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'allactors'));
   }

   public function update(Actor $actor): void
   {
       $item = $this->cache->get((string) 'actor' . $actor->getId());

       if ($item) {
           $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'actor' . $actor->getId()));
           $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'allactors'));
       }

       $this->actorRepository->update($actor);
   }

}