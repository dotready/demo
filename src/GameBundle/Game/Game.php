<?php

namespace GameBundle\Game;

use GameBundle\Entity\Setup;

abstract class Game
{
    abstract public function preSendSetup(Setup $setup): Setup;

    public function createSetup($difficulty, $playCount)
    {
        $method = 'create' . ucfirst($difficulty) . 'Setup';
        return $this->$method($playCount);
    }
}
