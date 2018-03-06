<?php

namespace App\Bundle\FilmBundle\Film\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowFilmController extends Controller
{
    public function showFilmAction(int $id)
    {
        $cache = $this->get('app.filecache');
        $hit = $cache->fetch('findOneById' . $id);
        if(!$hit) {
            $film = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);
            $cache->store('findOneById' . $id, $film);
            $this->addFlash("fail", "Cache not hit");
        } else {
            $film = $hit;
            $this->addFlash("success", "Cache hit");
        }

        return $this->render('@Twig_views/film/showFilm.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'film' => $film
        ]);
    }
}