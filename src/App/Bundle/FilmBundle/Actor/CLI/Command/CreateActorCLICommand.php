<?php

namespace App\Bundle\FilmBundle\Actor\CLI\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Component\Film\Application\Command\Actor\CreateActorCommand;
use App\Component\Film\Application\CommandHandler\Actor\CreateActorHandler;
use App\Component\Film\Domain\Repository\ActorRepository;
use App\Component\Film\Domain\Exception\{InvalidArgumentException, RepositoryException};
use App\Bundle\FilmBundle\Services\Cache\Exception\IOErrorException;

class CreateActorCLICommand extends Command
{
    protected static $defaultName = 'app:create-actor';
    private $createActor;
    private $actorRepository;
    private $entityManager;

    public function __construct(CreateActorHandler $createActor, ActorRepository $actorRepository, EntityManager $entityManager)
    {
        $this->createActor = $createActor;
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
        $actorName = $input->getArgument('name');
        $name = filter_var($actorName ?? '', FILTER_SANITIZE_STRING);

        $command = new CreateActorCommand($name);

        try {
            $this->createActor->handle($command);
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
        } catch (IOErrorException $e) {
            $output->writeln([
                'Actor Creator',
                '============',
                'Error: ' . $e->getMessage(),
            ]);
        }

    }
}