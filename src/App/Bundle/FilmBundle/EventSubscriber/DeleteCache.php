<?php

namespace App\Bundle\FilmBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\Event;

class DeleteCache extends Event
{
    const TOPIC = "deletecache";

    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }

}