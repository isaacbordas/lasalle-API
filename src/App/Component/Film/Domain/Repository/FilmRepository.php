<?php

namespace App\Component\Film\Domain\Repository;

use App\Component\Film\Domain\Film;

interface FilmRepository
{
    public function findById(int $filmId);
    public function findAllOrderedByName();
    public function save(Film $film): void;
    public function update(Film $film): void;
    public function delete(Film $film): void;
}