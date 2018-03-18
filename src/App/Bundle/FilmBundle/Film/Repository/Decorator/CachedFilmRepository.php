<?php

namespace App\Bundle\FilmBundle\Film\Repository\Decorator;

use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;
use App\Bundle\FilmBundle\Service\CacheService;

final class CachedFilmRepository implements FilmRepository
{
    private $filmRepository;
    private $cache;

    public function __construct(FilmRepository $filmRepository, CacheService $cache)
    {
        $this->filmRepository = $filmRepository;
        $this->cache = $cache;
    }

    public function findById(int $filmId): Film
    {
        $item = $this->cache->fetch((string) 'Film' . $filmId);

        if (!$item) {
            $item = $this->filmRepository->findById($filmId);

            $this->cache->save((string) 'Film' . $filmId, $item);
        }

        return $this->cache->fetch((string) 'Film' . $filmId);
    }

    public function findAllOrderedByName()
    {
        $item = $this->cache->fetch((string) 'AllFilms');

        if (!$item) {
            $item = $this->filmRepository->findAllOrderedByName();

            $this->cache->save((string) 'AllFilms', $item);
        }

        return $this->cache->fetch((string) 'AllFilms');
    }

    public function delete(Film $film): void
    {
        $item = $this->cache->fetch((string) 'Film' . $film->getId());

        if ($item) {
            $this->filmRepository->delete($film);
            $this->cache->cacheClear((string) 'Film' . $film->getId());
        }
    }

    public function save(Film $film): void
    {
        $this->filmRepository->save($film);
    }

    public function update(Film $film): void
    {
        $item = $this->cache->fetch((string) 'Film' . $film->getId());

        if ($item) {
            $this->cache->cacheClear((string) 'Film' . $film->getId());
        }

        $this->filmRepository->update($film);
    }
}