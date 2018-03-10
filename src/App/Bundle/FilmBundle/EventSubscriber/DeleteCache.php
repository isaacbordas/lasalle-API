<?php

namespace App\Bundle\FilmBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\Event;

class DeleteCache extends Event
{
    const TOPIC = "deletecache";
}