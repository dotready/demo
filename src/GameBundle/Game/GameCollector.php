<?php

namespace GameBundle\Game;

class GameCollector
{
    private $games;

    public function __construct()
    {
        $this->games = [];
    }

    public function addGame(GameInterface $game, string $alias)
    {
        $this->games[$alias] = $game;
    }

    public function getGame($alias)
    {
        if (array_key_exists($alias, $this->games)) {
            return $this->games[$alias];
        } else {
            throw new \Exception('Game does not exist');
        }
    }
}
