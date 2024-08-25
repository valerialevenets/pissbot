<?php

class Responses
{
    public function getResponses()
    {
        return [
            'Ага, тримай рота ширше',
            'Шо ти мелеш? Насцяв тобі на лице',
            'Насцяв тобі на капелюх'
        ];
    }

    public function getRandomResponse(): string
    {
        $responses = $this->getResponses();
        return $responses[array_rand($responses)];
    }
}
