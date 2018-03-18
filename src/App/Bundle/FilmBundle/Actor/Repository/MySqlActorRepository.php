<?php

namespace App\Bundle\FilmBundle\Actor\Repository;

use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;
use App\Component\Film\Domain\Exception\RepositoryException;
use App\Component\Film\Domain\Exception\UnknownActorException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MySqlActorRepository implements ActorRepository
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function save(Actor $actor): void
    {
        try {
            $this->em->persist($actor);
        } catch (Exception $e) {
            throw RepositoryException::withError($e);
        }
    }

    public function update(Actor $actor): void
    {
        $this->save($actor);
    }

    public function delete(Actor $actor): void
    {
        try {
            $this->em->remove($actor);
        } catch (Exception $e) {
            throw RepositoryException::withError($e);
        }
    }

    public function findById(int $actorId, bool $cache = false): Actor
    {
        $actor = $this->em
            ->getRepository('FilmBundle:Actor')
            ->findBy(['id' => $actorId]);
        if (count($actor) === 0) {
            throw UnknownActorException::withActorId($actorId);
        }
        return $actor[0];
    }
    public function findAllOrderedByName(bool $cache = false): array
    {
        try {
            return $this->em
                ->createQuery(
                    'SELECT o FROM \App\Component\Film\Domain\Actor o ORDER BY o.name ASC'
                )
                ->getResult();
        } catch (Exception $e) {
            throw RepositoryException::withError($e);
        }
    }
}