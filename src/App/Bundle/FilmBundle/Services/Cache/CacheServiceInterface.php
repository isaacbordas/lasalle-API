<?php

namespace App\Bundle\FilmBundle\Services\Cache;

use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class CacheServiceInterface extends Controller implements CacheInterface{
    
    public function set($key, $data, $ttl = null): void {}

    public function get($key, $default = null) {}

    public function delete($key): void {}

    public function clear(): void {}

    public function deleteMultiple($keys) {}

    public function getMultiple($keys, $default = null) {}

    public function setMultiple($values, $ttl = null) {}

    public function has($key) {}

    public function getFileName(string $key) {}
}
