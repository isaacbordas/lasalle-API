<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Doctrine\ORM\Query;
use App\Component\Film\Domain\Film;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListFilmsController extends Controller
{
    public function findAll()
    {
        $cache = $this->get('app.cacheservice');

        $hit = $cache->fetch('findAllFilmsViaAPI');
        if(!$hit) {
            $films = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findAll(Query::HYDRATE_ARRAY);
            try{
                $cache->store('findAllFilmsViaAPI', $films);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $films = $hit;
        }

        $filmsAsArray = array_map(function (Film $f) {
            return $this->filmToArray($f);
        }, $films);
        return new JsonResponse($filmsAsArray);
    }

    public function findById(int $id)
    {
        $cache = $this->get('app.cacheservice');

        $hit = $cache->fetch('findOneFilmByIdViaAPI' . $id);
        if(!$hit) {
            $film[] = $this->getDoctrine()->getRepository('\App\Component\Film\Domain\Film')->findOneBy(['id' => $id]);
            try{
                $cache->store('findOneFilmByIdViaAPI' . $id, $film);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $film[] = $hit;
        }

        $filmsAsArray = array_map(function (Film $f) {
            return $this->filmToArray($f);
        }, $film);
        return new JsonResponse($filmsAsArray);
    }

    private function filmToArray(Film $film)
    {
        return [
            'id' => $film->getId(),
            'name' => $film->getName(),
            'description' => $film->getDescription(),
            'actorName' => $film->getActor()
        ];
    }
}