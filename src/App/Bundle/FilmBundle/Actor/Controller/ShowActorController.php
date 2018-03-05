<?php

namespace App\Bundle\FilmBundle\Actor\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowActorController extends Controller
{
    public function showActorAction($id)
    {

        $actor = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Actor')->findOneBy(['id' => $id]);

        return $this->render('@Twig_views/actor/showActor.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'actor' => $actor
        ]);
    }
}