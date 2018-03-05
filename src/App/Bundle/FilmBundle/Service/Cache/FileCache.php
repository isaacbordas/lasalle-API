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

    public function store($key, $data, $ttl = 3600) {
        // Opening the file
        $h = fopen($this->getFileName($key),'w');
        if (!$h) throw new Exception('Could not write to cache');
        // Serializing along with the TTL
        $data = serialize(array(time()+$ttl,$data));
        if (fwrite($h,$data)===false) {
            throw new Exception('Could not write to cache');
        }
        fclose($h);

    }

    private function getFileName($key) {

        $path = $this->filecachepath;
        if(!is_dir($path)) {
            mkdir($path);
        }

        return $path . md5($key);

    }

    function fetch($key) {

        $filename = $this->getFileName($key);
        if (!file_exists($filename) || !is_readable($filename)) return false;

        $data = file_get_contents($filename);

        $data = @unserialize($data);
        if (!$data) {

            // Unlinking the file when unserializing failed
            unlink($filename);
            return false;

        }

        // checking if the data was expired
        if (time() > $data[0]) {

            // Unlinking
            unlink($filename);
            return false;

        }
        return $data[1];
    }
}