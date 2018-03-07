<?php

namespace App\Bundle\FilmBundle\Actor\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShowActorController extends Controller
{
    public function showActorAction(int $id, Request $request)
    {
        $cache = $this->get('app.filecache');

        $hit = $cache->fetch('findOneActorById' . $id . $request->getLocale());
        if(!$hit) {
            $actor = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Actor')->findOneBy(['id' => $id]);
            try{
                $cache->store('findOneActorById' . $id . $request->getLocale(), $actor);
            } catch (\Exception $e) {
                throw $e;
            }
            $this->addFlash("fail", "Cache not hit");
        } else {
            $actor = $hit;
            $this->addFlash("success", "Cache hit");
        }

        return $this->render('@Twig_views/actor/showActor.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'actor' => $actor
        ]);
    }
}