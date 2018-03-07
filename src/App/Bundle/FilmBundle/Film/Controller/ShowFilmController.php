<?php

namespace App\Bundle\FilmBundle\Film\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShowFilmController extends Controller
{
    public function showFilmAction(int $id, Request $request)
    {
        $cache = $this->get('app.filecache');

        $hit = $cache->fetch('findOneById' . $id . $request->getLocale());
        if(!$hit) {
            $film = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);
            $cache->store('findOneById' . $id . $request->getLocale(), $film);
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