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
            'пуза',
            'выборы',
            'вибори',
            'вибрали',
            'пердеж',
            'пердёж',
            'пердьож',
            'пердун',
            'кринж',
            'я не сбу',
            'я не тцк',
            'поцреот',
            'баби',
            'бабы',
        ];
    }
}
