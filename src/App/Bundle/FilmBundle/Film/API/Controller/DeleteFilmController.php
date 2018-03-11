<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteFilmController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $id = $json['id'];

        $deleteFilmUseCase = $this->get('app.film.usecase.delete');
        $deleteFilmUseCase->execute($id);

        return new JsonResponse('', 204);
    }
}