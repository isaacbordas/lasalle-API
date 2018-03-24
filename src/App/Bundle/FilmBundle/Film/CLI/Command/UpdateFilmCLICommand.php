<?php

namespace App\Bundle\FilmBundle\Film\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Film\UpdateFilmCommand;
use App\Component\Film\Application\CommandHandler\Film\UpdateFilmHandler;
use App\Component\Film\Domain\Repository\FilmRepository;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class UpdateFilmCLICommand extends Command
{
    protected static $defaultName = 'app:update-film';
    private $updateFilm;
    private $filmRepository;
    private $entityManager;

    public function __construct(UpdateFilmHandler $updateFilm, FilmRepository $filmRepository, EntityManager $entityManager)
    {
        $this->updateFilm = $updateFilm;
        $this->filmRepository = $filmRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:update-film')
            ->setDescription('Updates a new actor.')
            ->setHelp('This command allows you to update a actor...')
            ->addArgument('filmId', InputArgument::REQUIRED, 'Film ID')
            ->addArgument('name', InputArgument::OPTIONAL, 'Film name')
            ->addArgument('description', InputArgument::OPTIONAL, 'Film description')
            ->addArgument('actorId', InputArgument::OPTIONAL, 'Film Actor ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filmName = $input->getArgument('name');
        $filmDescription = $input->getArgument('description');
        $actorId = $input->getArgument('actorId');
        $filmId = $input->getArgument('filmId');

        $name = filter_var($filmName ?? '', FILTER_SANITIZE_STRING);
        $description = filter_var($filmDescription ?? '', FILTER_SANITIZE_STRING);
        $aId = filter_var($actorId ?? '', FILTER_SANITIZE_NUMBER_INT);
        $id = filter_var($filmId ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new UpdateFilmCommand($name, $description, $aId, $id);

        try {
            $this->updateFilm->handle($command);
            $this->entityManager->flush();
            $output->writeln([
                'Film Update',
                '============',
                $name,
            ]);
        } catch (InvalidArgumentException $e) {
            $output->writeln([
                'Film Update',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        } catch (RepositoryException $e) {
            $output->writeln([
                'Film Update',
                '============',
                'Error: An application error has occurred',
            ]);
        } catch (IOErrorException $e) {
            $output->writeln([
                'Film Update',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        }

    }
}