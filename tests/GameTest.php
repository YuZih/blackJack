<?php

declare (strict_types = 1);

use App\Game;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testGameInitialization(): void
    {
        $game = new Game();
        $this->assertInstanceOf(Game::class, $game);
    }
}
