<?php declare(strict_types = 1);

namespace App\Scenario\Controllers\Cron;

use App\Core\CronControllerInterface;
use App\Scenario\Services\ExampleService;
use RedBeanPHP\OODBBean;

class ExampleCronTask implements CronControllerInterface
{

    /** @var \App\Scenario\Services\ExampleService */
    private $service;

    public function __construct(ExampleService $service)
    {
        $this->service = $service;
    }

    public function handle(OODBBean $user_bean): void
    {
        // something
    }

}