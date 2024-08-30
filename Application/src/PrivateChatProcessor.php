<?php

namespace Application;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class PrivateChatProcessor
{
    private Api $telegram;
    private RelayProcessor $relay;

    public function __construct(Api $telegram, RelayProcessor $relay)
    {
        $this->telegram = $telegram;
        $this->relay = $relay;
    }

    public function processMessage(Message $message)
    {
        if ($message->hasCommand() && str_contains($message->text, '/reply')) {
            $this->relay->relay($message);
        } else {
            $this->telegram->sendMessage(
                [
                    'chat_id' => $message->chat->id,
                    'text' =>   'жуй сраку',
                ]
            );
        }
    }
}