<?php

declare(strict_types=1);

namespace App\Core\Console\Commands;

use App\Core\Application;
use App\Core\CronControllerInterface;
use App\Scenario\Controllers\Cron\ExampleCronTask;
use App\Scenario\Repositories\UserRepository;
use Cekta\DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunForCrontabCommand extends Command
{

    protected static $defaultName = 'run:cron';
    private $commands = [
        ExampleCronTask::class
    ];

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @var \App\Scenario\Repositories\UserRepository
     */
    private $user_repository;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->user_repository = $container->get(UserRepository::class);
        parent::__construct('run:cron');
    }

    protected function configure(): void
    {
        $this->setDescription('run background bot lifecycle only once');
    }

    /**
     * @param  \Symfony\Component\Console\Input\InputInterface    $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     *
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->user_repository->all()->get();
        foreach ($users as $user) {
            foreach ($this->commands as $commandName)  {
                $command = $this->container->get($commandName);
                if ($command instanceof CronControllerInterface) {
                    $command->handle($user);
                }
            }
        }
    }
}
