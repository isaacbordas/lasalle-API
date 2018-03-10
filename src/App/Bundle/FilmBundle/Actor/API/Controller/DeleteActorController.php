<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DeleteActorController extends Controller
{
    public function execute($id)
    {
        $deleteActorUseCase = $this->get('app.actor.usecase.deleteactor');
        $deleteActorUseCase->execute($id);

        return new Response('', 204);
    }
}