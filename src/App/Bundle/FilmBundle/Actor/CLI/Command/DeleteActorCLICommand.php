<?php

namespace App\Bundle\FilmBundle\Actor\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Actor\DeleteActorCommand;
use App\Component\Film\Application\CommandHandler\Actor\DeleteActorHandler;
use App\Component\Film\Domain\Repository\ActorRepository;

class DeleteActorCLICommand extends Command
{
    protected static $defaultName = 'app:delete-actor';
    private $deleteActor;
    private $actorRepository;
    private $entityManager;

    public function __construct(DeleteActorHandler $deleteActor, ActorRepository $actorRepository, EntityManager $entityManager)
    {
        $this->deleteActor = $deleteActor;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:delete-actor')
            ->setDescription('Deletes a actor.')
            ->setHelp('This command allows you to delete a actor...')
            ->addArgument('actorId', InputArgument::REQUIRED, 'Actor ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actorId = $input->getArgument('actorId');
        $id = filter_var($actorId ?? '', FILTER_SANITIZE_NUMBER_INT);

        $command = new DeleteActorCommand($id);

        try {
            $actor = $this->deleteActor->handle($command);
            $this->entityManager->flush();
            $output->writeln([
                'Actor Delete',
                '============',
                'Deleted ' . $actor->getName(),
            ]);
        } catch (InvalidArgumentException $e) {
            $output->writeln([
                'Actor Delete',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        } catch (RepositoryException $e) {
            $output->writeln([
                'Actor Delete',
                '============',
                'Error: An application error has occurred',
            ]);
        }

    }
}