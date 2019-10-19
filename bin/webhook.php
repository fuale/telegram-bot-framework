<?php declare(strict_types = 1);

use App\Core\Application;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$runner = (new Application())->getRunner();
$runner->runViaWebhook();