<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Application\Exception\InvalidActorNameException;

class CreateActorController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $name = filter_var($json['name'] ?? '', FILTER_SANITIZE_STRING);

        try {
            $createActorUseCase = $this->get('app.actor.usecase.create');
            $createActorUseCase->execute($name);
            return new JsonResponse(['result' => 'Actor created'], 201);
        } catch (InvalidActorNameException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

    }
}