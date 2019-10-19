<?php declare(strict_types = 1);

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{

    protected $name = 'start';

    protected $description = 'Start command';

    protected $usage = '/start';

    protected $version = '1.1.0';

    protected $need_mysql = true;

    /** @throws \Longman\TelegramBot\Exception\TelegramException */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $conversation = new Conversation($user_id, $chat_id, $this->name);

        if ($conversation->exists()) {
            // you can authorize user at this step

            // for message sending
            // $this->replyToUser( some message );

            $conversation->stop();
        }

        return Request::emptyResponse();
    }

}
