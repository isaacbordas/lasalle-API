<?php

namespace App\Component\Film\Domain\Repository;

interface ActorRepository
{
    public function findById(int $actorId);

    public function findAllOrderedByName();
}
