<?php

namespace GameBundle\Game;

use Demo\Enum\ImageCategory;
use DemoBundle\Library\Image\Processor;
use DemoBundle\Service\ImageService;
use GameBundle\Entity\Setup;

class Mastermind extends Game implements GameInterface
{
    /**
     * @var array
     */
    private $colors = [
        'black', 'white', 'yellow', 'orange', 'brown', 'red', 'blue', 'green'
    ];

    /**
     *
     * @param $move
     * @param $state
     *
     * @return integer
     */
    public function makeMove($move, $state): int
    {
        return 0;
    }

    /**
     *
     * @param $move
     * @param $state
     *
     * @return bool
     */
    public function validateMove($move, $state): bool
    {
        return true;
    }

    /**
     * @param $state
     *
     * @return bool
     */
    public function isCompleted($state): bool
    {
        return false;
    }

    public function makeSeed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return $sec + $usec * 1000000;
    }

    /**
     * @param Setup $setup
     *
     * @return Setup
     */
    public function preSendSetup(Setup $setup): Setup
    {
        $data = $setup->getData();
        unset($data->colors);
        $setup->setData(json_encode($data));
        return $setup;
    }

    /**
     * Create easy game setup
     *
     * @param int $playCount
     *
     * @return array
     */
    public function createEasySetup(int $playCount): array
    {
        $colors = [];

        for ($i = 0; $i < 4; $i++) {
            srand($this->makeSeed());
            $randval = rand(0, count($this->colors) -1);
            $colors[] = $this->colors[$randval];
        }

        $setup = [
            'colors' => $colors,
            'canvasWidth' => 440,
            'canvasHeight' => 500
        ];

        return $setup;
    }

    /**
     * Last chance of modifying the
     * game setup date before
     * sending it to the client
     *
     * @todo use decorators for this
     *
     * @param array $setup
     * @return array
     */
    public function createClientSetup(array $setup): array
    {
        return $setup;
    }

    /**
     * Indicates if the game
     * should have a random startup
     * instead of predefined
     *
     * @return bool
     */
    public function randomizeGameSetup(): bool
    {
        return true;
    }
}
