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

        $film = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);

        $updateFilmUseCase = $this->get('app.film.usecase.update');
        $updateFilmUseCase->execute($json, $film);

        return new JsonResponse(['result' => 'Film updated'], 200);
    }
}