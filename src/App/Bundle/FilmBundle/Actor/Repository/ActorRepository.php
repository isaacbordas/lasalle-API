<?php

namespace App\Bundle\FilmBundle\Actor\Repository;

use App\Component\Film;
use Doctrine\ORM\EntityRepository;

class ActorRepository extends EntityRepository implements Film\Domain\Repository\ActorRepository
{
    public function findById(int $actorId)
    {
        return $this->findOneBy(['id' => $actorId]);
    }

    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT o FROM \App\Component\Film\Domain\Actor o ORDER BY o.name ASC'
            )
            ->getResult();
    }
}
