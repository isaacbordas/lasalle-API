<?php

namespace App\Bundle\FilmBundle\Actor\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Actor\ReadActorAllCommand;
use App\Component\Film\Application\CommandHandler\Actor\ReadActorAllHandler;
use App\Component\Film\Application\Command\Actor\ReadActorByIdCommand;
use App\Component\Film\Application\CommandHandler\Actor\ReadActorByIdHandler;
use App\Component\Film\Domain\Actor;
use App\Component\Film\Domain\Repository\ActorRepository;

class ReadActorCLICommand extends Command
{
    protected static $defaultName = 'app:list-actor';
    private $readActorAll;
    private $readActorById;
    private $actorRepository;
    private $entityManager;

    public function __construct(ReadActorAllHandler $readActorAll, ReadActorByIdHandler $readActorById, ActorRepository $actorRepository, EntityManager $entityManager)
    {
        $this->readActorAll = $readActorAll;
        $this->readActorById = $readActorById;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:list-actor')
            ->setDescription('List actors.')
            ->setHelp('This command allows you to list actors...')
            ->addArgument('actorId', InputArgument::OPTIONAL, 'Actor ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actorId = $input->getArgument('actorId');

        if(!empty($actorId)) {
            $command = new ReadActorByIdCommand($actorId);

            try {
                $actor = $this->readActorById->handle($command);
                $output->writeln([
                    'Actor List by ID',
                    '============',
                    ''
                ]);
                $output->writeln('ID: ' . $actorId . ' - Name: ' . $actor->getName());
            } catch (InvalidArgumentException $e) {
                $output->writeln([
                    'Actor List by ID',
                    '============',
                    'Error: ' . $e->getMessage(),
                ]);
            } catch (RepositoryException $e) {
                $output->writeln([
                    'Actor List by ID',
                    '============',
                    'Error: An application error has occurred',
                ]);
            }
        } else {
            try {
                $text = '';
                $actors = $this->readActorAll->handle();
                $actorsAsArray = array_map(function (Actor $a) {
                    return $this->actorToArray($a);
                }, $actors);

                $output->writeln([
                    'Actor List',
                    '============',
                    '',
                ]);

                foreach ($actorsAsArray as $actor):
                    $output->writeln('ID: ' . $actor['id'] . ' - Name: ' . $actor['name']);
                endforeach;



            } catch (InvalidArgumentException $e) {
                $output->writeln([
                    'Actor List',
                    '============',
                    'Error: ' . $e->getMessage(),
                ]);
            } catch (RepositoryException $e) {
                $output->writeln([
                    'Actor List',
                    '============',
                    'Error: An application error has occurred',
                ]);
            }
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