<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Application\Command\Actor\ReadActorByIdCommand;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class ListActorsController extends Controller
{
    public function findAll(): JsonResponse
    {
        $handler = $this->get('app.actor.command_handler.read_all');

        try {
            $actors = $handler->handle();

            $actorsAsArray = array_map(function (Actor $a) {
                return $this->actorToArray($a);
            }, $actors);

            return new JsonResponse(
                $actorsAsArray,
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

    public function findById(Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(), true);
        $id = filter_var($json['id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new ReadActorByIdCommand($id);
        $handler = $this->get('app.actor.command_handler.read_by_id');

        try {
            $actor = $handler->handle($command);
            return new JsonResponse(
                ['actor' => $actor->toArray()],
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

    private function actorToArray(Actor $actor)
    {
        return [
            'id' => $actor->getId(),
            'name' => $actor->getName()
        ];
    }

}