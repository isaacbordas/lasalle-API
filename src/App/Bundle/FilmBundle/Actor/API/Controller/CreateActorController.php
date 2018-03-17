<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Application\Command\Actor\CreateActorCommand;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Exception\InvalidArgumentException;
use App\Component\Film\Domain\Exception\RepositoryException;

class CreateActorController extends Controller
{
    public function execute(Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(), true);
        $name = filter_var($json['name'] ?? '', FILTER_SANITIZE_STRING);

        $command = new CreateActorCommand($name);
        $handler = $this->get('app.actor.command_handler.create');

        try {
            $actor = $handler->handle($command);
            $this->get('doctrine.orm.default_entity_manager')->flush();
            return new JsonResponse(
                ['success' => 'Actor correctly created', 'actor' => $actor->toArray()],
                201
            );
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RepositoryException $e) {
            return new JsonResponse(['error' => 'An application error has occurred'], 500);
        }

    }
}