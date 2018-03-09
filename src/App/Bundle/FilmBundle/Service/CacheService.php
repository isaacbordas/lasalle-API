<?php

namespace App\Bundle\FilmBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CacheService extends Controller implements \App\Component\Film\Application\Service\CacheService
{
    private $filecachepath;

    public function __construct(string $filecachepath)
    {
        $this->filecachepath = $filecachepath;
    }

    public function store(string $key, $data) : void
    {
        $handler = fopen($this->getFileName($key),'w');
        if (!$handler) {
            throw new Exception('Could not write to cache');
        }

        $serializedobject = serialize($data);

        if (fwrite($handler, $serializedobject) === false) {
            throw new Exception('Could not write to cache');
        }
        fclose($handler);
    }

    public function getFileName(string $key) : string
    {
       $path = $this->filecachepath;
        if(!is_dir($path)) {
            mkdir($path);
        }

        return $path . md5($key);
    }

    function fetch(string $key)
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

    public function cacheClear() : void
    {
        $cachedir = $this->filecachepath;

        $files = glob($cachedir . '/*');

        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
    }
}