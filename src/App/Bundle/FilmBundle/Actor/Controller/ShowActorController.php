<?php

namespace App\Bundle\FilmBundle\Actor\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Application\Command\Actor\ReadActorByIdCommand;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowActorController extends Controller
{
    public function showActorAction(int $id)
    {
        $actorId = filter_var($id ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new ReadActorByIdCommand($actorId);
        $handler = $this->get('app.actor.command_handler.read_by_id');

        try {
            $actor = $handler->handle($command);
            return $this->render('@Twig_views/actor/showActor.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'actor' => $actor
            ]);

        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RepositoryException $e) {
            return new JsonResponse(['error' => 'An application error has occurred'], 500);
        } catch (IOErrorException $e) {
            return new JsonResponse(['error' => $e], 500);
        }

    }
}