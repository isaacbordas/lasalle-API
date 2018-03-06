<?php

namespace App\Bundle\FilmBundle\Service\Cache;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileCache extends Controller
{
    private $filecachepath;

    public function __construct($filecachepath)
    {
        $this->filecachepath = $filecachepath;
    }

    public function store(string $key, object $data, int $ttl = 3600) : void
    {
        $handler = fopen($this->getFileName($key),'w');
        if (!$handler) {
            throw new Exception('Could not write to cache');
        }

        $data = serialize(array(time()+$ttl, $data));

        if (fwrite($handler, $data) === false) {
            throw new Exception('Could not write to cache');
        }
        fclose($handler);
    }

    private function getFileName(string $key) : string
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

        $data = @unserialize($data);
        if (!$data) {
            unlink($filename);
            return false;
        }

        if (time() > $data[0]) {
            unlink($filename);
            return false;
        }

        return $data[1];
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