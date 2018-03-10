<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Doctrine\ORM\Query;
use App\Component\Film\Domain\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListActorsController extends Controller
{
    public function findAll()
    {
        $cache = $this->get('app.cacheservice');

        $hit = $cache->fetch('findAllActorsViaAPI');
        if(!$hit) {
            $actors = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Actor')->findAll(Query::HYDRATE_ARRAY);
            try{
                $cache->store('findAllActorsViaAPI', $actors);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $actors = $hit;
        }

        $actorsAsArray = array_map(function (Actor $a) {
            return $this->actorToArray($a);
        }, $actors);
        return new JsonResponse($actorsAsArray);
    }

    public function findById(int $id)
    {
        $cache = $this->get('app.cacheservice');

        $hit = $cache->fetch('findOneActorByIdViaAPI' . $id);
        if(!$hit) {
            $actor[] = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Actor')->findOneBy(['id' => $id]);
            try{
                $cache->store('findOneActorByIdViaAPI' . $id, $actor);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $actor[] = $hit;
        }

        $actorsAsArray = array_map(function (Actor $a) {
            return $this->actorToArray($a);
        }, $actor);
        return new JsonResponse($actorsAsArray);
    }

    private function actorToArray(Actor $actor)
    {
        return [
            'id' => $actor->getId(),
            'name' => $actor->getName()
        ];
    }
}