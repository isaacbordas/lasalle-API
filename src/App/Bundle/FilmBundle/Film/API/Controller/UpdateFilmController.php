<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Application\Command\Film\UpdateFilmCommand;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class UpdateFilmController extends Controller
{
    public function execute(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);
        $name = filter_var($json['name'] ?? '', FILTER_SANITIZE_STRING);
        $description = filter_var($json['description'] ?? '', FILTER_SANITIZE_STRING);
        $actorId = filter_var($json['actorId'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        $filmId = filter_var($id ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new UpdateFilmCommand($name, $description, $actorId, $filmId);
        $handler = $this->get('app.film.command_handler.update');

        try {
            $film = $handler->handle($command);
            $this->get('doctrine.orm.default_entity_manager')->flush();
            return new JsonResponse(
                ['success' => 'Film correctly updated', 'film' => $film->toArray()],
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
}