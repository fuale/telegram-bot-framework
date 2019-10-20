<?php

declare(strict_types=1);

namespace App\Core\Console\Commands;

use App\Core\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetWebhookCommand extends Command
{

    protected static $defaultName = 'configure:webhook';

    protected function configure(): void
    {
        $this->setDescription('set weebhook');
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
        $telegram = $runner->makeTelegram();
        $output->writeln('enter webhook url:');
        $telegram->setWebhook(trim(fgets(STDIN)));
        $output->writeln(ob_get_clean());
    }
}
