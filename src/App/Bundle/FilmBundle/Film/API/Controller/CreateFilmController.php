<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateFilmController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $name = $json['name'];
        $description = $json['description'];
        $actorId = $json['actorId'];

        $createFilmUseCase = $this->get('app.film.usecase.create');
        $createFilmUseCase->execute($name, $description, $actorId);
        return new JsonResponse(['result' => 'Film created'], 201);
    }
}