<?php

namespace App\Bundle\FilmBundle\Film\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Component\Film\Domain\Film;
use App\Component\Film\Application\Command\Film\ReadFilmByIdCommand;
use App\Component\Film\Domain\Exception\InvalidArgumentException;
use App\Component\Film\Domain\Exception\RepositoryException;

class ShowFilmController extends Controller
{
    public function showFilmAction(int $id)
    {
        $filmId = filter_var($id ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new ReadFilmByIdCommand($filmId);
        $handler = $this->get('app.film.command_handler.read_by_id');

        try {
            $film = $handler->handle($command);
            return $this->render('@Twig_views/film/showFilm.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'film' => $film
            ]);

        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RepositoryException $e) {
            return new JsonResponse(['error' => 'An application error has occurred'], 500);
        }
    }
}