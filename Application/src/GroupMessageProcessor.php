<?php

namespace Application;

use Application\ValueObject\PublicResponses;
use Application\ValueObject\SerhiiResponses;
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
            $this->replyToMessage($message, SerhiiResponses::getRandomResponse());
        } elseif ($this->isSharii($message)) {
            $this->telegram->sendVideo(
                [
                    'chat_id' => $message->get('chat')['id'],
                    'reply_to_message_id' => $message->get('message_id'),
                    'video' => 'BAACAgIAAx0Cek9ncgACEg9m1fc4ezod_wwdSXsEsnSkwPufnAAC21gAAnZosUq95zDukVWloDUE' ,
                ]
            );
        } elseif (PublicResponses::hasResponse(trim(mb_strtolower($message->text)))) {
            $this->replyToMessage($message, PublicResponses::getResponse(trim(mb_strtolower($message->text))));
        }
    }

    private function isSharii(Message $message): bool
    {
        if (!empty($message->forwardFromChat) && $message->forwardFromChat->username == 'ASupersharij') {
            return true;
        }
        return false;
    }


    function isMessageSuitable(Message $message): bool {
        if($message->has('chat') && $this->isSerhii($message)) {
            foreach(Triggers::getTriggers() as $trigger) {
                if (str_contains(mb_strtolower($message->get('text')), $trigger)) {
                    return true;
                }
            }
            return str_contains(mb_strtolower($message->get('text')), 'banana');
        }
        return false;
    }
    function replyToMessage(Message $message, string $text): void
    {
        $this->telegram->sendMessage(
            [
                'chat_id' => $message->get('chat')['id'],
                'reply_to_message_id' => $message->get('message_id'),
                'text' => $text
            ]
        );
    }

    function isSerhii(Message $message): bool {
        $id = $message->get('from')['id'];
//         return $id == 273718429;
        return $id == 942380502;
    }

}