<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Component\Film\Domain\Film;
use App\Component\Film\Application\Command\Film\ReadFilmByIdCommand;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class ListFilmsController extends Controller
{
    public function findAll(): JsonResponse
    {
        $handler = $this->get('app.film.command_handler.read_all');

        try {
            $films = $handler->handle();

            $filmsAsArray = array_map(function (Film $f) {
                return $this->filmToArray($f);
            }, $films);

            return new JsonResponse(
                $filmsAsArray,
                200
            );
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RepositoryException $e) {
            return new JsonResponse(['error' => 'An application error has occurred'], 500);
        } catch (IOErrorException $e) {
            return new JsonResponse(['error' => $e], 500);
        }
    }

    public function findById(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $id = filter_var($json['id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new ReadFilmByIdCommand($id);
        $handler = $this->get('app.film.command_handler.read_by_id');

        try {
            $film = $handler->handle($command);
            return new JsonResponse(
                ['film' => $film->toArray()],
                200
            );
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RepositoryException $e) {
            return new JsonResponse(['error' => 'An application error has occurred'], 500);
        } catch (IOErrorException $e) {
            return new JsonResponse(['error' => $e], 500);
        }
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