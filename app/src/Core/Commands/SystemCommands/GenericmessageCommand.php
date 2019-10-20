<?php

declare(strict_types=1);

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{

    protected $name = 'genericmessage';

    protected $description = 'Handle generic message';

    protected $version = '1.1.0';

    protected $need_mysql = true;

    /**
     * @return ServerResponse|mixed
     * @throws TelegramException
     */
    public function execute()
    {
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        if (
            $conversation->exists()
            && ($command = $conversation->getCommand())
        ) {
            return $this->telegram->executeCommand($command);
        }

        return Request::emptyResponse();
    }
}
