<?php declare(strict_types = 1);

namespace App\Core\Console\Commands;

use App\Core\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunForCrontabCommand extends Command
{

    protected static $defaultName = 'run:cron';

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
        ob_start();
        $runner = (new Application())->getRunner();
        $runner->runCron();
        $output->writeln(ob_get_clean());
    }

}