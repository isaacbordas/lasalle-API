<?php

namespace App\Bundle\FilmBundle\Actor\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Application\Command\Actor\DeleteActorCommand;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class DeleteActorController extends Controller
{
    public function execute(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $id = filter_var($json['id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new DeleteActorCommand($id);
        $handler = $this->get('app.actor.command_handler.delete');

        try {
            $handler->handle($command);
            $this->get('doctrine.orm.default_entity_manager')->flush();
            return new JsonResponse(
                '',
                204
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