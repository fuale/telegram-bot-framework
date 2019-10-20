<?php

declare(strict_types=1);

namespace App\Core;

use RedBeanPHP\OODBBean;

interface CronControllerInterface
{

    public function handle(OODBBean $user_bean): void;
}
