<?php declare(strict_types = 1);

namespace App\Core;

use App\Scenario\Services\TelegramClientInterface;
use GuzzleHttp\Client;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class Runner
 *
 * @package App\Core
 */
class Runner
{

    private $bot;

    private $mysql;

    private $paths;

    /** @var \App\Core\CronClassesLoader */
    private $cron_classes_loader;

    /**
     * Runner constructor.
     *
     * @param  array                        $bot
     * @param  array                        $mysql
     * @param  array                        $paths
     * @param  \App\Core\CronClassesLoader  $cron_classes_loader
     * @param  \GuzzleHttp\Client           $telegram_client
     *
     * @throws \Exception
     */
    public function __construct(
        array $bot,
        array $mysql,
        array $paths,
        CronClassesLoader $cron_classes_loader,
        Client $telegram_client
    ) {
        $this->bot = $bot;
        $this->mysql = $mysql;
        $this->paths = $paths;

        TelegramLog::initialize(
            new Logger('telegram_bot', [
                (new StreamHandler($this->paths['logs'] . '/telegram_debug.log',
                    Logger::DEBUG))->setFormatter(new LineFormatter(null, null,
                    true)),
                (new StreamHandler($this->paths['logs'] . '/telegram_error.log',
                    Logger::ERROR))->setFormatter(new LineFormatter(null, null,
                    true)),
            ]),

            new Logger('telegram_bot_updates', [
                (new StreamHandler($this->paths['logs'] . '/telegram_update.log',
                    Logger::INFO))->setFormatter(new LineFormatter('%message%'
                    . PHP_EOL)),
            ])
        );

        \assert($telegram_client instanceof Client);
        Request::setClient($telegram_client);
        $this->cron_classes_loader = $cron_classes_loader;
    }

    /**
     * @throws \Exception
     */
    public function runViaGetUpdates(): void
    {
        try {
            $telegram = $this->makeTelegram();
            $telegram->handleGetUpdates();
        } catch (TelegramException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return \Longman\TelegramBot\Telegram
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function makeTelegram(): Telegram
    {
        [$key, $name] = $this->bot;
        $telegram = new Telegram($key, $name);
        $telegram->enableLimiter();
        $telegram->enableMySql($this->mysql);
        $telegram->addCommandsPaths([
            $this->paths['commands'] . '/SystemCommands',
            $this->paths['commands'] . '/AdminCommands',
            $this->paths['commands'] . '/UserCommands',
        ]);

        $telegram->setDownloadPath($this->paths['download']);
        $telegram->setUploadPath($this->paths['upload']);
        return $telegram;
    }

    /**
     * @throws \RedBeanPHP\RedException
     */
    public function runCron(): void
    {
        $this->cron_classes_loader->load();
    }

    public function runViaWebhook(): void
    {
        try {
            $telegram = $this->makeTelegram();
            $telegram->handle();
        } catch (TelegramException $e) {
            echo $e->getMessage();
        }
    }

}