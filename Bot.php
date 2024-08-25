<?php

require __DIR__.'/vendor/autoload.php';
require_once 'Triggers.php';
require_once 'Responses.php';

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class Bot {
    private ?Api $telegram = null;
    private int $latestUpdateId = 1;
    private Triggers $triggers;
    private Responses $responses;
// $latestUpdateId+= 545730153;
// Example usage
// var_dump(cou);
// var_dump($telegram->getUpdates(['allowed_updates' => ['message']])); die;

    public function __construct()
    {
        $this->telegram = new Api('');
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
    /**@var Update */
    // var_dump($updates); die;
    foreach($updates as $update) {
        $this->processSingleUpdate($update);
        // var_dump($message->get('text')); die;
    }
}

function processSingleUpdate(Update $update) {
    $this->latestUpdateId = $update->get('update_id')+1;//increase by 1
    // var_dump($update); die;
    $message = $update->getMessage();
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
    return $id == 273718429;
    return $id == 942380502;
}
}

$bot = new Bot();
$id = $bot->getLatestUpdateId();
for(;;) {
    $bot->process();
    sleep(1);
}
