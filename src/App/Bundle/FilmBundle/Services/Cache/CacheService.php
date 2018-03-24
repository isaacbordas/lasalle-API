<?php

namespace App\Bundle\FilmBundle\Services\Cache;

use App\Bundle\FilmBundle\EventSubscriber\DeleteCache;
use App\Bundle\FilmBundle\Services\Cache\Exception\{Exception, IOErrorException};

class CacheService extends CacheServiceInterface
{
    private $filecachepath;

    public function __construct(string $filecachepath)
    {
        $this->filecachepath = $filecachepath;
    }

    public function set($key, $data, $ttl = null): void
    {
        $handler = fopen($this->getFileName($key),'w');
        if (!$handler) {
            throw new IOErrorException('Could not write to cache', 500);
        }

        $serializedobject = serialize($data);

        if (fwrite($handler, $serializedobject) === false) {
            throw new IOErrorException('Could not write to cache', 500);
        }
        fclose($handler);
    }

    public function getFileName(string $key): string
    {
       $path = $this->filecachepath;
        if(!is_dir($path)) {
            mkdir($path);
        }

        return $path . md5($key);
    }

    function get($key, $default = null)
    {
       $filename = $this->getFileName($key);
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $data = file_get_contents($filename);

        $unserializedobject = unserialize($data);
        if (!$unserializedobject) {
            unlink($filename);
            return false;
        }

        return $unserializedobject;
    }

    public function delete($key): void
    {
        $filename = $this->getFileName($key);
        if (file_exists($filename) || is_readable($filename)) {
            unlink($filename);
        }
    }

    public function clear() : void
    {
        $cachedir = $this->filecachepath;
        $files = glob($cachedir . '/*');
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
    }

    public function onDeletecache(DeleteCache $deleteCache)
    {
        $this->delete($deleteCache->getKey());
    }

}