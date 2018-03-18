<?php

namespace App\Bundle\FilmBundle\Film\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Film\DeleteFilmCommand;
use App\Component\Film\Application\CommandHandler\Film\DeleteFilmHandler;
use App\Component\Film\Domain\Repository\FilmRepository;

class DeleteFilmCLICommand extends Command
{
    protected static $defaultName = 'app:delete-film';
    private $deleteFilm;
    private $filmRepository;
    private $entityManager;

    public function __construct(DeleteFilmHandler $deleteFilm, FilmRepository $filmRepository, EntityManager $entityManager)
    {
        $this->deleteFilm = $deleteFilm;
        $this->filmRepository = $filmRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:delete-film')
            ->setDescription('Deletes a film.')
            ->setHelp('This command allows you to delete a film...')
            ->addArgument('filmId', InputArgument::REQUIRED, 'Film ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filmId = $input->getArgument('filmId');
        $id = filter_var($filmId ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new DeleteFilmCommand($id);

        try {
            $film = $this->deleteFilm->handle($command);
            $this->entityManager->flush();
            $output->writeln([
                'Film Delete',
                '============',
                'Deleted ' . $film->getName(),
            ]);
        } catch (InvalidArgumentException $e) {
            $output->writeln([
                'Film Delete',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        } catch (RepositoryException $e) {
            $output->writeln([
                'Film Delete',
                '============',
                'Error: An application error has occurred',
            ]);
        }
    }
}