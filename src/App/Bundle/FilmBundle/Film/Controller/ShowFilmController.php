<?php

namespace App\Bundle\FilmBundle\Film\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowFilmController extends Controller
{
    public function showFilmAction($id)
    {

        $cache = $this->get('app.filecache');
        $hit = $cache->fetch('findOneById' . $id);
        if(!$hit) {
            $film = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);
            $cache->store('findOneById' . $id, $film);
        } else {
            $film = $hit;
        }

        //$film = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);

        return $this->render('@Twig_views/film/showFilm.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'film' => $film
        ]);
    }
}