<?php

namespace App\Bundle\FilmBundle\Film\Repository;

use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;
use App\Component\Film\Domain\Exception\RepositoryException;
use App\Component\Film\Domain\Exception\UnknownActorException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MySqlFilmRepository implements FilmRepository
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function save(Film $film): void
    {
        try {
            $this->em->persist($film);
        } catch (Exception $e) {
            throw RepositoryException::withError($e);
        }
    }

    public function update(Film $film): void
    {
        $this->save($film);
    }

    public function delete(Film $film): void
    {
        try {
            $this->em->remove($film);
        } catch (Exception $e) {
            throw RepositoryException::withError($e);
        }
    }

    public function findById(int $filmId, bool $cache = false): Film
    {
        $film = $this->em
            ->getRepository('FilmBundle:Film')
            ->findBy(['id' => $filmId]);
        if (count($film) === 0) {
            throw UnknownActorException::withActorId($filmId);
        }
        return $film[0];
    }
    public function findAllOrderedByName(bool $cache = false): array
    {
        try {
            return $this->em
                ->createQuery(
                    'SELECT o FROM \App\Component\Film\Domain\Film o ORDER BY o.name ASC'
                )
                ->getResult();
        } catch (Exception $e) {
            throw RepositoryException::withError($e);
        }
    }
}