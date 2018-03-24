<?php

namespace App\Bundle\FilmBundle\Film\Repository\Decorator;

use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;
use App\Bundle\FilmBundle\Services\Cache\CacheService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Bundle\FilmBundle\EventSubscriber\DeleteCache;
use App\Bundle\FilmBundle\Services\Cache\Exception\{Exception, IOErrorException};

final class CachedFilmRepository implements FilmRepository
{
    private $filmRepository;
    private $cache;
    private $dispatcher;

    public function __construct(FilmRepository $filmRepository, CacheService $cache, EventDispatcherInterface $dispatcher)
    {
        $this->filmRepository = $filmRepository;
        $this->cache = $cache;
        $this->dispatcher = $dispatcher;
    }

    public function findById(int $filmId, bool $cache = true): Film
    {
        if($cache == false){
            $item = $this->filmRepository->findById($filmId);
        }else {
            $item = $this->cache->get((string)'film' . $filmId);

            if (!$item) {
                $item = $this->filmRepository->findById($filmId);

                $this->cache->set((string)'film' . $filmId, $item);
            }
        }

        return $item;
    }

    public function findAllOrderedByName(bool $cache = true)
    {
        $item = $this->cache->get((string) 'allfilms');

        if (!$item) {
            $item = $this->filmRepository->findAllOrderedByName();

            $this->cache->set((string) 'allfilms', $item);
        }

        return $item;
    }

    public function delete(Film $film): void
    {
        $item = $this->cache->get((string) 'film' . $film->getId());

        if ($item) {
            $this->filmRepository->delete($film);
            $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'film' . $film->getId()));
            $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'allfilms'));
        }
    }

    public function save(Film $film): void
    {
        $this->filmRepository->save($film);
        $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'allfilms'));
    }

    public function update(Film $film): void
    {
        $item = $this->cache->get((string) 'film' . $film->getId());

        if ($item) {
            $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'film' . $film->getId()));
            $this->dispatcher->dispatch(DeleteCache::TOPIC, new DeleteCache((string) 'allfilms'));
        }

        $this->filmRepository->update($film);
    }
}