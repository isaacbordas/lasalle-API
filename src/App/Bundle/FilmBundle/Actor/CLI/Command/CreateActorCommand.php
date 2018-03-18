<?php

namespace App\Bundle\FilmBundle\Actor\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Actor\CreateActorCommand as CreateActor;
use App\Component\Film\Application\CommandHandler\Actor\CreateActorHandler;
use App\Component\Film\Domain\Repository\ActorRepository;

class CreateActorCommand extends Command
{
    protected static $defaultName = 'app:create-actor';
    private $createactor;
    private $actorRepository;
    private $entityManager;

    public function __construct(CreateActorHandler $createactor, ActorRepository $actorRepository, EntityManager $entityManager)
    {
        $this->createactor = $createactor;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-actor')
            ->setDescription('Creates a new actor.')
            ->setHelp('This command allows you to create a actor...')
            ->addArgument('name', InputArgument::REQUIRED, 'Actor name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $command = new CreateActor($name);

        try {
            $actor = $this->createactor->handle($command);
            $this->actorRepository->save($actor);
            $this->entityManager->flush();
            $output->writeln([
                'Actor Creator',
                '============',
                $name,
            ]);
        } catch (InvalidArgumentException $e) {
            $output->writeln([
                'Actor Creator',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        } catch (RepositoryException $e) {
            $output->writeln([
                'Actor Creator',
                '============',
                'Error: An application error has occurred',
            ]);
        }

    }
}