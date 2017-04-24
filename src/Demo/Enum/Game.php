<?php

namespace Demo\Enum;

class Game
{
    const MASTERMIND = 'mastermind';

    public static function getGames()
    {
        return [self::MASTERMIND];
    }
}
