<?php

namespace GameBundle\Game;

use GameBundle\Game\State\StateManager;

class GameManager
{
    /**
     * @var GameCollector
     */
    private $gameCollector;

    /**
     * @var StateManager
     */
    private $stateManager;

    /**
     * GameManager constructor.
     *
     * @param GameCollector $gameCollector
     * @param StateManager $stateManager
     */
    public function __construct(GameCollector $gameCollector, StateManager $stateManager)
    {
        $this->gameCollector = $gameCollector;
        $this->stateManager = $stateManager;
    }

    /**
     * Construct the Game class
     * from the given State
     *
     * The state class name MUST match
     * the type of Game
     *
     * Request the Game if this
     * was a valid move
     *
     * @param $move
     * @param $state
     *
     * @throws \Exception
     *
     * @return void
     */
    public function validateMove($move, $state)
    {
        $reflection = new \ReflectionClass($state);
        $gameId = strtolower($reflection->getShortName());

        $game = $this->gameCollector->getGame($gameId);
        $isValid = $game->validateMove($move, $state);

        if ($isValid === false) {
            throw new \Exception('illegal move');
        }

        return $isValid;
    }

    public function makeMove($move, $state)
    {
        $reflection = new \ReflectionClass($state);
        $gameId = strtolower($reflection->getShortName());

        $game = $this->gameCollector->getGame($gameId);
        return $game->makeMove($move, $state);
    }

    /**
     * Construct the Game class
     * from the given State
     *
     * The state class name MUST match
     * the type of Game
     *
     * Request the Game if it is completed
     * with the given current state
     *
     * @param $state
     *
     * @return boolean
     */
    public function isCompleted($state): bool
    {
        $reflection = new \ReflectionClass($state);
        $gameId = strtolower($reflection->getShortName());

        $game = $this->gameCollector->getGame($gameId);
        return $game->isCompleted($state);
    }
}
