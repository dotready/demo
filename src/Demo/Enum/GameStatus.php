<?php

namespace Demo\Enum;

class GameStatus
{
    const START = 'game_start';

    const PROGRESS = 'game_progress';

    const PLAYING = 'playing';

    const COMPLETED = 'completed';

    const FAILED = 'failed';

    const FORFEIT = 'forfeit';

    const CHEAT_ATTEMPT = 'cheat_attempt';

    const INSUFFICIENT_CREDITS = 'insufficient_credits';

    const SESSION_IN_PROGRESS = 'session_in_progress';
}
