<?php

namespace App\Bundle\FilmBundle\Services\Cache;

interface CacheServiceInterface {
    
    public function save(string $key, $data): void;
    
    public function getFileName(string $key): string;
    
    public function fetch(string $key);
    
    public function deleteKey(string $key): void;

    public function clearCache(): void;

}
