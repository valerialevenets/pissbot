<?php

namespace Application\ValueObject;
class Triggers
{

    public static function getTriggers(): array
    {
        return [
            'наголосовали',
            'понавыбирали',
            "понавибирали",
            'пузо',
            'выборы',
            'вибори',
            'вибрали',
            'пердеж',
            'пердёж',
            'пердьож',
            'кринж',
            'я не сбу',
            'я не тцк',
            'поцреот',
            'баби',
            'бабы',
        ];
    }
}
