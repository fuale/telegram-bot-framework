<?php

declare(strict_types=1);

use App\Core\Application;
use App\Core\Runner;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = new Container();
$container->get(Runner::class);
$runner = (new Application())->getRunner();
$runner->runViaWebhook();
