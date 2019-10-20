<?php

declare(strict_types=1);

namespace App\Core;

use App\Scenario\Repositories\UserRepository;
use Longman\TelegramBot\TelegramLog;
use Psr\Container\ContainerInterface;

class CronClassesLoader
{

    /** @var CronClassesCollection */
    private $collection;

    /** @var \App\Scenario\Repositories\UserRepository */
    private $user_repository;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    public function __construct(
        UserRepository $user_repository,
        CronClassesCollection $collection,
        ContainerInterface $container
    ) {
        $this->container = $container;
        $this->collection = $collection;
        $this->user_repository = $user_repository;
    }

    /**
     * @throws \RedBeanPHP\RedException
     */
    public function load(): void
    {
        /** @var \RedBeanPHP\OODBBean[] $users */
        $users = $this->user_repository->all()->get();
        foreach ($users as $user) {
            while ($class = $this->collection->pop()) {
                try {
                    $object = $this->container->get($class);
                    if ($object instanceof CronControllerInterface) {
                        $object->handle($user);
                    } else {
                        throw new \RuntimeException(
                            sprintf('Class %s must be implementing %s', $class, CronControllerInterface::class)
                        );
                    }
                } catch (\Exception $exception) {
                    TelegramLog::error((string) $exception);
                    continue;
                }
            }
        }
    }
}
