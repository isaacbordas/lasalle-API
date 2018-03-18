<?php

namespace App\Bundle\FilmBundle\Service;

interface CacheServiceInterface {
    
    public function save(string $key, $data): void;
    
    public function getFileName(string $key): string;
    
    public function fetch(string $key);
    
    public function cacheClear(string $key): void;
    
}
