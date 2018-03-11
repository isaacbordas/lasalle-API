<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateActorController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $name = $json['name'];

        $createActorUseCase = $this->get('app.actor.usecase.create');
        $createActorUseCase->execute($name);
        return new JsonResponse(['result' => 'Actor created'], 201);
    }
}