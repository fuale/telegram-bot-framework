<?php declare(strict_types = 1);

namespace App\Core\Console\Commands;

use App\Core\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunViaGetUpdatesCommand extends Command
{

    protected static $defaultName = 'run:getupdates';

    protected function configure(): void
    {
        $this->setDescription('run bot lifecycle only once via get updates(long pooling) method');
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
        ob_start();
        $application = new Application();
        $runner = $application->getRunner();
        $runner->runViaGetUpdates();
        $output->writeln(ob_get_clean());
        $output->writeln('runned!');
    }

}