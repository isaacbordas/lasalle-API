<?php

namespace App\Bundle\FilmBundle\Film\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Film\CreateFilmCommand;
use App\Component\Film\Application\CommandHandler\Film\CreateFilmHandler;
use App\Component\Film\Domain\Repository\FilmRepository;

class CreateFilmCLICommand extends Command
{
    protected static $defaultName = 'app:create-film';
    private $createFilm;
    private $filmRepository;
    private $entityManager;

    public function __construct(CreateFilmHandler $createFilm, FilmRepository $filmRepository, EntityManager $entityManager)
    {
        $this->createFilm = $createFilm;
        $this->filmRepository = $filmRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-film')
            ->setDescription('Creates a new film.')
            ->setHelp('This command allows you to create a film...')
            ->addArgument('name', InputArgument::REQUIRED, 'Film name')
            ->addArgument('description', InputArgument::REQUIRED, 'Film description')
            ->addArgument('actorId', InputArgument::REQUIRED, 'Film actor (ID)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filmName = $input->getArgument('name');
        $filmDescription = $input->getArgument('description');
        $filmActorId = $input->getArgument('actorId');

        $name = filter_var($filmName ?? '', FILTER_SANITIZE_STRING);
        $description = filter_var($filmDescription ?? '', FILTER_SANITIZE_STRING);
        $actorId = filter_var($filmActorId ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new CreateFilmCommand($name, $description, $actorId);

        try {
            $this->createFilm->handle($command);
            $this->entityManager->flush();
            $output->writeln([
                'Film Creator',
                '============',
                $name,
            ]);
        } catch (InvalidArgumentException $e) {
            $output->writeln([
                'Film Creator',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        } catch (RepositoryException $e) {
            $output->writeln([
                'Film Creator',
                '============',
                'Error: An application error has occurred',
            ]);
        }

    }
}