<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateActorController extends Controller
{
    public function execute(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);

        $actor = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Actor')->findOneBy(['id' => $id]);

        $updateActorUseCase = $this->get('app.actor.usecase.update');
        $updateActorUseCase->execute($json, $actor);

        return new JsonResponse(['result' => 'Actor updated'], 200);
    }
}