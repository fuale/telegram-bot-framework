<?php

declare(strict_types=1);

namespace App\Core\Console;

use App\Core\Console\Commands\RunForCrontabCommand;
use App\Core\Console\Commands\RunViaGetUpdatesCommand;
use App\Core\Console\Commands\SetWebhookCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

class Application
{

    /**
     * Application constructor.
     *
     * @throws \Exception
     */
    public function __construct(RunForCrontabCommand $cronCommand)
    {
        $app = new ConsoleApplication('Conosle management', 'non-stable');
        $app->add(new RunViaGetUpdatesCommand());
        $app->add($cronCommand);
        $app->add(new SetWebhookCommand());
        $app->run();
    }
}
