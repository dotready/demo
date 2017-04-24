<?php

namespace GameBundle\Game;

interface GameInterface
{
    public function makeMove($move, $state);

    public function validateMove($move, $state): bool;

    public function isCompleted($state): bool;

    public function randomizeGameSetup(): bool;

    public function createEasySetup(int $playCount): array;
}
