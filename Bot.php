<?php

require __DIR__.'/vendor/autoload.php';
require_once 'Triggers.php';
require_once 'Responses.php';
require_once 'TokenProvider.php';

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class Bot {
    private ?Api $telegram = null;
    private int $latestUpdateId = 1;
    private Triggers $triggers;
    private Responses $responses;

    public function __construct()
    {
        $this->telegram = new Api(TokenProvider::getToken());
        $this->triggers = new Triggers();
        $this->responses = new Responses();
    }

    public function getLatestUpdateId(): int
    {
        return $this->latestUpdateId;
    }
    public function process()
    {
        $this->processUpdates($this->telegram->getUpdates(['offset' => $this->getLatestUpdateId(), 'allowed_updates' => ['message']]));
    }
function processUpdates(array $updates) {
    foreach($updates as $update) {
        try{
            $this->processSingleUpdate($update);
        } catch (\Exception $ex) {
            //yes, do nothing
        }
    }
}

function processSingleUpdate(Update $update) {
    $this->latestUpdateId = $update->get('update_id')+1;
    $message = $update->getMessage();
    if (! $message instanceof Message) {
        return;
    }
    if ($this->isMessageSuitable($message)) {
        $this->replyToMessage($message);
    }
}

function isMessageSuitable(Message $message): bool {
    if($message->has('chat') && $this->isSerhii($message)) {
        foreach($this->triggers->getTriggers() as $trigger) {
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
            'text' => $this->responses->getRandomResponse()
        ]
    );
}

function isSerhii(Message $message): bool {
    $id = $message->get('from')['id'];
    // return $id == 273718429;
    return $id == 942380502;
}
}

$bot = new Bot();
$id = $bot->getLatestUpdateId();
for(;;) {
    $bot->process();
    sleep(1);
}
