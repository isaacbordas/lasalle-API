<?php

namespace App\Component\Film\Domain\Repository;

interface ActorRepository
{
    public function findById($actorId);

    public function findAllOrderedByName();
}
