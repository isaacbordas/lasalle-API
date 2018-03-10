<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DeleteFilmController extends Controller
{
    public function execute($id)
    {
        $deleteFilmUseCase = $this->get('app.film.usecase.deletefilm');
        $deleteFilmUseCase->execute($id);

        return new Response('', 204);
    }
}