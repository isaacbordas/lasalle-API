<?php

namespace App\Bundle\FilmBundle\Actor\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Actor\UpdateActorCommand;
use App\Component\Film\Application\CommandHandler\Actor\UpdateActorHandler;
use App\Component\Film\Domain\Repository\ActorRepository;

class UpdateActorCLICommand extends Command
{
    protected static $defaultName = 'app:update-actor';
    private $updateActor;
    private $actorRepository;
    private $entityManager;

    public function __construct(UpdateActorHandler $updateActor, ActorRepository $actorRepository, EntityManager $entityManager)
    {
        $this->updateActor = $updateActor;
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
            ->addArgument('actorId', InputArgument::REQUIRED, 'Actor ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $actorId = $input->getArgument('actorId');

        $command = new UpdateActorCommand($name, $actorId);

        try {
            $this->updateActor->handle($command);
            $this->entityManager->flush();
            $output->writeln([
                'Actor Update',
                '============',
                $name,
            ]);
        } catch (InvalidArgumentException $e) {
            $output->writeln([
                'Actor Update',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        } catch (RepositoryException $e) {
            $output->writeln([
                'Actor Update',
                '============',
                'Error: An application error has occurred',
            ]);
        }

    }
}