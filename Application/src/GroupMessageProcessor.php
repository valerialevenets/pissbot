<?php

namespace Application;

use Application\ValueObject\Responses;
use Application\ValueObject\Triggers;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class GroupMessageProcessor
{
    private Api $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function process(Message $message)
    {
        if ($this->isMessageSuitable($message)) {
            $this->replyToMessage($message);
        }
    }

    function isMessageSuitable(Message $message): bool {
        if($message->has('chat') && $this->isSerhii($message)) {
            foreach(Triggers::getTriggers() as $trigger) {
                if (str_contains(strtolower($message->get('text')), $trigger)) {
                    return true;
                }
            }
            return str_contains(strtolower($message->get('text')), 'banana');
        }
        return false;
    }
    function replyToMessage(Message $message)
    {
        $this->telegram->sendMessage(
            [
                'chat_id' => $message->get('chat')['id'],
                'reply_to_message_id' => $message->get('message_id'),
                'text' => Responses::getRandomResponse()
            ]
        );
    }

    function isSerhii(Message $message): bool {
        $id = $message->get('from')['id'];
//         return $id == 273718429;
        return $id == 942380502;
    }

}