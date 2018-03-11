<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Application\Exception\InvalidActorNameException;

class UpdateActorController extends Controller
{
    public function execute(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);
        $name = filter_var($json['name'] ?? '', FILTER_SANITIZE_STRING);

        $actor = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Actor')->findOneBy(['id' => $id]);

        try {
            $updateActorUseCase = $this->get('app.actor.usecase.update');
            $updateActorUseCase->execute($name, $actor);
            return new JsonResponse(['result' => 'Actor updated'], 200);
        } catch (InvalidActorNameException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}