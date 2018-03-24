<?php

namespace App\Bundle\FilmBundle\Film\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Application\Command\Film\CreateFilmCommand;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class CreateFilmController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $name = filter_var($json['name'] ?? '', FILTER_SANITIZE_STRING);
        $description = filter_var($json['description'] ?? '', FILTER_SANITIZE_STRING);
        $actorId = filter_var($json['actorId'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new CreateFilmCommand($name, $description, $actorId);
        $handler = $this->get('app.film.command_handler.create');

        try {
            $film = $handler->handle($command);
            $this->get('doctrine.orm.default_entity_manager')->flush();
            return new JsonResponse(
                ['success' => 'Film correctly created', 'film' => $film->toArray()],
                201
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