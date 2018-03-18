<?php

namespace App\Bundle\FilmBundle\Film\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\CommandHandler\Film\ReadFilmAllHandler;
use App\Component\Film\Application\Command\Film\ReadFilmByIdCommand;
use App\Component\Film\Application\CommandHandler\Film\ReadFilmByIdHandler;
use App\Component\Film\Domain\Film;
use App\Component\Film\Domain\Repository\FilmRepository;

class ReadFilmCLICommand extends Command
{
    protected static $defaultName = 'app:list-film';
    private $readFilmAll;
    private $readFilmById;
    private $actorRepository;
    private $entityManager;

    public function __construct(ReadFilmAllHandler $readFilmAll, ReadFilmByIdHandler $readFilmById, FilmRepository $actorRepository, EntityManager $entityManager)
    {
        $this->readFilmAll = $readFilmAll;
        $this->readFilmById = $readFilmById;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:list-film')
            ->setDescription('List films.')
            ->setHelp('This command allows you to list films...')
            ->addArgument('filmId', InputArgument::OPTIONAL, 'Film ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filmId = $input->getArgument('filmId');

        if(!empty($filmId)) {
            $command = new ReadFilmByIdCommand($filmId);

            try {
                $film = $this->readFilmById->handle($command);
                $output->writeln([
                    'Film List by ID',
                    '============',
                    ''
                ]);
                $output->writeln('ID: ' . $filmId . ' - Name: ' . $film->getName());
            } catch (InvalidArgumentException $e) {
                $output->writeln([
                    'Film List by ID',
                    '============',
                    'Error: ' . $e->getMessage(),
                ]);
            } catch (RepositoryException $e) {
                $output->writeln([
                    'Film List by ID',
                    '============',
                    'Error: An application error has occurred',
                ]);
            }
        } else {
            try {
                $films = $this->readFilmAll->handle();
                $filmsAsArray = array_map(function (Film $f) {
                    return $this->filmToArray($f);
                }, $films);

                $output->writeln([
                    'Film List',
                    '============',
                    '',
                ]);

                foreach ($filmsAsArray as $film):
                    $output->writeln('ID: ' . $film['id'] . ' - Name: ' . $film['name']);
                endforeach;

            } catch (InvalidArgumentException $e) {
                $output->writeln([
                    'Film List',
                    '============',
                    'Error: ' . $e->getMessage(),
                ]);
            } catch (RepositoryException $e) {
                $output->writeln([
                    'Film List',
                    '============',
                    'Error: An application error has occurred',
                ]);
            }
        }
    }

    private function filmToArray(Film $film)
    {
        return [
            'id' => $film->getId(),
            'name' => $film->getName()
        ];
    }
}