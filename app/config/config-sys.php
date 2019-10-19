<?php declare(strict_types = 1);

use App\Core\CronClassesCollection;
use App\Scenario\Controllers\Cron\ExampleCronTask;
use Cekta\DI\Loader\Service;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use RedBeanPHP\Adapter\DBAdapter;
use RedBeanPHP\Driver\RPDO;
use RedBeanPHP\Finder;
use RedBeanPHP\OODB;
use RedBeanPHP\QueryWriter;
use RedBeanPHP\QueryWriter\MySQL;
use RedBeanPHP\ToolBox;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return [
    Client::class => new Service(static function (ContainerInterface $container) {
        [$base_uri, $proxy] = $container->get('telegram_client');

        return new Client([
            'base_uri' => $base_uri,
            'proxy'    => $proxy,
            'verify'   => false,
            'timeout'  => 0,
        ]);
    }),

    'paths' => [
        'root'     => dirname(__DIR__, 2),
        'storage'  => dirname(__DIR__, 2) . '/storage',
        'logs'     => dirname(__DIR__, 2) . '/storage/logs',
        'commands' => dirname(__DIR__) . '/Core/Commands',
        'download' => dirname(__DIR__, 2) . '/storage/upload',
        'upload'   => dirname(__DIR__, 2) . '/storage/download',
        'config'   => dirname(__DIR__) . '/config',
    ],

    Finder::class => new Service(static function (ContainerInterface $container) {
        return new Finder($container->get(ToolBox::class));
    }),

    RPDO::class => new Service(static function (ContainerInterface $container) {
        $mysql = $container->get('mysql');
        $rpdo = new RPDO(
            sprintf('mysql:host=%s;dbname=%s',
                $mysql['host'],
                $mysql['database']),
            $mysql['user'],
            $mysql['password']
        );

        $rpdo->connect();
        if ( ! $rpdo->isConnected()) {
            throw new RuntimeException('Could not connect to database');
        }

        return $rpdo;
    }),

    DBAdapter::class => new Service(static function (ContainerInterface $container) {
        return new DBAdapter($container->get(RPDO::class));
    }),

    QueryWriter::class => new Service(static function (ContainerInterface $container) {
        return new MySQL($container->get(DBAdapter::class));
    }),

    ToolBox::class => new Service(static function (ContainerInterface $container) {
        $adapter = $container->get(DBAdapter::class);
        $writer = $container->get(QueryWriter::class);
        return new ToolBox(new OODB($writer), $adapter, $writer);
    }),

    OODB::class => new Service(static function (ContainerInterface $container) {
        return $container->get(ToolBox::class)->getRedBean();
    }),

    CronClassesCollection::class => new Service(static function () {
        $collection = new CronClassesCollection();
        $collection->push(ExampleCronTask::class);
        return $collection;
    }),

    FilesystemAdapter::class => new Service(static function () {
        /**
         * Create with default constructor params (fix autowiring)
         */
        return new FilesystemAdapter();
    }),
];