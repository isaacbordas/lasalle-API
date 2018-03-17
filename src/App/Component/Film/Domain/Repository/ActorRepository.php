<?php

namespace App\Component\Film\Domain\Repository;

use App\Component\Film\Domain\Actor;

interface ActorRepository
{
    public function findById(int $actorId);
    public function findAllOrderedByName();
    public function save(Actor $actor): void;
    public function update(Actor $actor): void;
    public function delete(Actor $actor): void;
}
