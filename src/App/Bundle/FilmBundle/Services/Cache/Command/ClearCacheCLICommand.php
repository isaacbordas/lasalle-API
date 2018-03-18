<?php

namespace App\Bundle\FilmBundle\Services\Cache\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Bundle\FilmBundle\Services\Cache\CacheService;

class ClearCacheCLICommand extends Command
{
    protected static $defaultName = 'app:clear-cache';
    private $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:clear-cache')
            ->setDescription('Deletes all the cache.')
            ->setHelp('This command allows you to delete the entire cache...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cache->clearCache();

        $output->writeln([
            'Cache deleted',
            '============',
            '',
        ]);
    }

}