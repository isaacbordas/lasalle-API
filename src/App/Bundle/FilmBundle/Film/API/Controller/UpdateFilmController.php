<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateFilmController extends Controller
{
    public function execute(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);
        $name = filter_var($json['name'] ?? '', FILTER_SANITIZE_STRING);
        $description = filter_var($json['description'] ?? '', FILTER_SANITIZE_STRING);
        $actorId = filter_var($json['actorId'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $film = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);

        $updateFilmUseCase = $this->get('app.film.usecase.update');
        $updateFilmUseCase->execute($name, $description, $actorId, $film);

        return new JsonResponse(['result' => 'Film updated'], 200);
    }
}