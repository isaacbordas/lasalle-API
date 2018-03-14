<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Application\Exception\InvalidActorIdException;

class DeleteActorController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $id = filter_var($json['id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        try {
            $deleteActorUseCase = $this->get('app.actor.usecase.delete');
            $deleteActorUseCase->execute($id);
            return new JsonResponse('', 204);
        } catch (InvalidActorIdException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

    }
}