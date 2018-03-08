<?php

namespace App\Component\Film\Application\Service;

interface CacheService {
    
    public function store(string $key, $data) : void;
    
    public function getFileName(string $key) : string;
    
    public function fetch(string $key);
    
    public function cacheClear() : void;
    
}
