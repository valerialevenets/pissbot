<?php

namespace Application;

use Application\ValueObject\PublicResponses;
use Application\ValueObject\SerhiiResponses;
use Application\ValueObject\Triggers;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\File;
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
        if(!empty($message->photo)) {
//            var_dump($message->photo->last()->toArray());
            /** @var File $file */
            $file = $this->telegram->getFile($message->photo->last()->toArray());

            var_dump($this->telegram->getFile($message->photo->last()->toArray()));
        }
//        var_dump($message);
        ;
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
        } elseif (PublicResponses::hasResponse($this->removeEmoji(trim(mb_strtolower($message->text))))) {
            $this->replyToMessage($message, PublicResponses::getResponse($this->removeEmoji(trim(mb_strtolower($message->text)))));
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
    private function removeEmoji($string): string
    {
        // Match Enclosed Alphanumeric Supplement
        $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
        $clear_string = preg_replace($regex_alphanumeric, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Supplemental Symbols and Pictographs
        $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
        $clear_string = preg_replace($regex_supplemental, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}